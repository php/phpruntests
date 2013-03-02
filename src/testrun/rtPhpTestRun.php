<?php
/**
 * rtTestRun
 *
 * Main class for a single test run
 *
 * @category   Testing
 * @package    RUNTESTS
 * @author     Zoe Slattery <zoe@php.net>
 * @author     Stefan Priebsch <spriebsch@php.net>
 * @author     Georg Gradwohl <g2@php.net>
 * @copyright  2009 The PHP Group
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 *
 */
class rtPhpTestRun
{
    protected $commandLineArguments;
    protected $runConfiguration;
    protected $redirectedTestCases = array();
    protected $resultList = array(); //An array of arrays of Group Results
    protected $serialTasks = array();
    protected $parallelTasks = array();
    protected $reportStatus = 0;
    protected $numberOfSerialGroups = 0;
    protected $numberOfParallelGroups = 0;
    protected $processorCount;
    protected $runStartTime;
    protected $skippedGroups = array();
    protected $logFileName;
    protected $groupTasks = false;

    public function __construct($argv)
    {
        $this->commandLineArguments = $argv;
    }

    public function run()
    {
        $this->runStartTime = microtime(true);
        //Set SSH variables

        // check the operation-system (win/unix)
        $os = (substr(PHP_OS, 0, 3) == "WIN") ? 'Windows' : 'Unix';

        //Configure the test environment
        $this->runConfiguration = rtRuntestsConfiguration::getInstance($this->commandLineArguments, $os);
        $this->runConfiguration->getUserEnvironment();
        $this->runConfiguration->configure();



        //Check help message
        if($this->runConfiguration->hasCommandLineOption('help') || $this->runConfiguration->hasCommandLineOption('h')) {
            echo rtText::get('help');
            exit;
        }

        //Check the preconditions
        $preConditionList = rtPreConditionList::getInstance($os);
        $preConditionList->adaptList();

        // $preConditionList->check($this->commandLine, $this->environmentVariables);
        $preConditionList->check($this->runConfiguration);

        //Write PHP executable name to the array of env variables. Some
        //test cases expect to be able to use it.
        $php = $this->runConfiguration->getSetting('PhpExecutable');
        $this->runConfiguration->setEnvironmentVariable('TEST_PHP_EXECUTABLE', $php);

        //Set reporting option
        $this->setReportStatus();

        $this->processorCount = $this->requestedProcessorCount();

        if($this->runConfiguration->hasCommandLineOption('log')) {
            $this->logFileName = $this->runConfiguration->getCommandLineOption('log');
            file_put_contents($this->logFileName, "");
        }
         
        if($this->runConfiguration->hasCommandlineOption('g')) {
            $this->groupTasks = true;
        }


        /*
         * Main decision point. Either we start this with a directory (or set of directories, in which case tests are
         * run as a group (and in parallel if required) or......
         */
        if ($this->runConfiguration->getSetting('TestDirectories') != null) {

            $this->doGroupRuns();

        } else {

            /*
             *... the input is a test file, or list of files and are just run as single tests
             * and not in parallel
             */
            if ($this->runConfiguration->getSetting('TestFiles') == null) {
                echo rtText::get('invalidTestFileName');
                exit();
            }else{
                $this->run_tests($this->runConfiguration->getSetting('TestFiles'));
            }
        }

        if(count($this->redirectedTestCases) > 0) {
            $this->doRedirectedRuns();
        }

        if(($this->numberOfSerialGroups != 0) || ($this->numberOfParallelGroups != 0))    {
            $this->createRunOutput();
        }

    }

    public function doGroupRuns() {

        $subDirectories = $this->buildSubDirectoryList($this->runConfiguration->getSetting('TestDirectories'));

        //An array of group configuration objects, one for each subdirectory.
        $groupConfigurations = $this->buildGroupConfigurations($subDirectories);


        //If there is only one subdirectory, run seqential

        if(count($subDirectories) === 1) {
            $this->run_serial_groups($subDirectories, $groupConfigurations);
            $this->numberOfSerialGroups = 1;

        } else {

            //check to see if this is set to be a parallel run, if not, run the subdirectory groups in sequence.
            if($this->processorCount <= 1) {
                $this->run_serial_groups($subDirectories, $groupConfigurations);
                $this->numberOfSerialGroups = count($subDirectories);
            } else {
                //At least part of this run can be in parallel, check group configurations to make sure that none are set to be serial.
                //This builds parallel and serial task lists.
                foreach($groupConfigurations as $key=>$gc) {
                    if($gc->isSerial()) {
                        $serialGroups[] = $key;
                    } else {
                        $parallelGroups[] = $key;
                    }
                }

                if(isset($serialGroups)) {$this->numberOfSerialGroups = count($serialGroups);}

                $this->numberOfParallelGroups = count($parallelGroups);

                $this->run_parallel_groups($parallelGroups, $groupConfigurations, $this->processorCount);
                if($this->numberOfSerialGroups > 0)    {
                    $this->run_serial_groups($serialGroups, $groupConfigurations);
                }
            }
        }

    }

    public function doRedirectedRuns() {
        foreach($this->redirectedTestCases as $testCase){
             
            $groupConfig = new rtGroupConfiguration(null);
            $groupConfig->parseRedirect($testCase);
             
            $group = $groupConfig->getTestDirectory();
             
            $this->run_serial_groups(array($group), array($group=>$groupConfig));
             
            $this->numberOfSerialGroups++;
             
        }
    }

    public function run_parallel_groups($testDirectories, $groupConfigurations, $processCount) {

        //Create the task list to be executed in parallel. Either randomly or trying to order it.
        $taskList = array();
        if($this->groupTasks == false) {
            foreach($testDirectories as $testGroup) {
                $taskList[] = new rtTaskTestGroup($this->runConfiguration, $testGroup, $groupConfigurations[$testGroup]);
            }
        }else {
            $taskList = $this->groupTasksByWeight($testDirectories, $groupConfigurations);

        }


        // run the task-scheduler
        $scheduler = rtTaskScheduler::getInstance();
        $scheduler->setTaskList($taskList);
        $scheduler->setProcessCount($processCount);
        $scheduler->setReportStatus($this->reportStatus);
        $scheduler->run();

        foreach($scheduler->getResultList() as $groupResult) {

            if($groupResult->isSkipGroup()) {
                $this->skippedGroups[] = $groupResult->getGroupName();
            } else {
                $this->resultList[] = $groupResult->getTestStatusList();
            }

            // Logging - get which group was run by which processor and how long each took

            if($this->runConfiguration->hasCommandLineOption('log')) {

                $groupTime = round($groupResult->getTime(), 2);
                $runTime = $groupResult->getAbsTime() - $this->runStartTime;
               
                $runTime = round($runTime, 2);

                $string = "PARLOG," . $groupResult->getGroupName() .
                          "," . $groupTime . 
                          "," . $runTime .
                          "," . $groupResult->getProcessorId() . 
                          "," . $groupResult->getRunOrder() ."\n";

                file_put_contents($this->logFileName, $string, FILE_APPEND);

            }

            $redirects = $groupResult->getRedirectedTestCases();
            foreach($redirects as $testCase) {
                $this->redirectedTestCases[] = $testCase;
            }
        }


         
    }

    public function run_serial_groups($testDirectories, $groupConfigurations) {

        $groupCount = 0;

        foreach($testDirectories as $subDirectory) {

            // Memory usage logging
            $startMemory = memory_get_usage();

            $testGroup = new rtPhpTestGroup($this->runConfiguration, $subDirectory, $groupConfigurations[$subDirectory]);
         

            if($testGroup->isSkipGroup() === true) {
                $this->skippedGroups[] = $testGroup->getGroupName();
                continue;
            }

            $testGroup->run();

            rtTestOutputWriter::flushResult($testGroup->getGroupResults()->getTestStatusList(), $this->reportStatus);
            $this->resultList[] = $testGroup->getGroupResults()->getTestStatusList();
             
            if($this->runConfiguration->hasCommandLineOption('log')) {
                 
                $time = round($testGroup->getGroupResults()->getTime(), 2);

                $absTime = ($testGroup->getGroupResults()->getAbsTime()) - $this->runStartTime;
                
                $absTime = round($absTime, 2);
                               

                $string =  "SERLOG," . $testGroup->getGroupName() . "," .
                $time . "," .
                $absTime . "," .
                $testGroup->getGroupResults()->getProcessorId() . "," .
                $groupCount . "\n";

                file_put_contents($this->logFileName, $string, FILE_APPEND);

            }
             
            $redirects = $testGroup->getGroupResults()->getRedirectedTestCases();
            foreach($redirects as $testCase) {
                $this->redirectedTestCases[] = $testCase;
            }

             
            $testGroup->__destruct();
            unset($testGroup);
             
            if($this->runConfiguration->hasCommandLineOption('log')) {
                $string = "MEMLOG," . $subDirectory . ", " . $startMemory. ", " .memory_get_usage() . "\n";
                file_put_contents($this->logFileName, $string, FILE_APPEND);
            }
            
            $groupCount++;          
        }
    }

    public function run_tests($testNames) {

        //This section deals with running single test cases, or lists of test cases.

        foreach ($testNames as $testName) {

            if (!file_exists($testName)) {
                echo rtText::get('invalidTestFileName', array($testName));
                exit();
            }


            //Read the test file
            $testFile = new rtPhpTestFile();
            $testFile->doRead($testName);
            $testFile->normaliseLineEndings();

            $testStatus = new rtTestStatus($testFile->getTestName());


            if ($testFile->arePreconditionsMet()) {
                $testCase = new rtPhpTest($testFile->getContents(), $testFile->getTestName(), $testFile->getSectionHeadings(), $this->runConfiguration, $testStatus);

                //Setup and set the local environment for the test case
                $testCase->executeTest($this->runConfiguration);

                $results = new rtTestResults($testCase);
                $results->processResults($testCase, $this->runConfiguration);
                $summaryResults = array($testFile->getTestName() => $results->getStatus());

            } elseif (in_array("REDIRECTTEST", $testFile->getSectionHeadings())) {

                //Redirect handler
                //Build a list of redirected test cases

                $this->redirectedTestCases[] = new rtPhpTest($testFile->getContents(), $testFile->getTestName(), $testFile->getSectionHeadings(), $this->runConfiguration, $testStatus);

                $testStatus->setTrue('redirected');
                $testStatus->setMessage('redirected', $testFile->getExitMessage());
                $summaryResults = array($testFile->getTestName() => $testStatus);

            } else {
                $testStatus->setTrue('bork');
                $testStatus->setMessage('bork', $testFile->getExitMessage());
                $summaryResults = array($testFile->getTestName() => $testStatus);
            }
             
            rtTestOutputWriter::flushResult($summaryResults, 3);

        }
    }

    public function buildSubDirectoryList($testDirectories){


        $subDirectories = array();
        foreach ($testDirectories as $testDirectory) {
            $subDirectories = array_merge($subDirectories, rtUtil::parseDir($testDirectory));
        }
        return $subDirectories;
    }

    public function requestedProcessorCount() {
        // check for the cmd-line-option 'z' which defines parellel-execution
        $processCount = 0;
        if ($this->runConfiguration->hasCommandLineOption('z')) {

            $processCount = intval($this->runConfiguration->getCommandLineOption('z'));

            if (!is_numeric($processCount) || $processCount < 0) {
                $processCount = 2;
            }
        }
        return $processCount;
    }

    public function buildGroupConfigurations($subDirectories) {
        $groupConfigurations = array();
        foreach($subDirectories as $subDir) {
            $groupConfig = new rtGroupConfiguration($subDir);
            $groupConfig->parseConfiguration();
            $groupConfigurations[$subDir] = $groupConfig;
        }
        return $groupConfigurations;
    }

    public function buildRedirectsList($results) {
        foreach ($results as $groupResult) {
            foreach($groupResult as $testResult) {
                if($testResult->getStatus() == 'redirected') {
                    $this->redirectedTestCases[] = $testResult->getRedirectedTestCase();
                }
            }
        }
    }

    public function createRunOutput() {
        $type = null;
        if ($this->runConfiguration->hasCommandLineOption('o')) {
            $type = $this->runConfiguration->getCommandLineOption('o');
        }

        $outputWriter = rtTestOutputWriter::getInstance($type);
        $outputWriter->setResultList($this->resultList);

        $outputWriter->printOverview($this->numberOfParallelGroups, $this->numberOfSerialGroups, $this->processorCount, $this->skippedGroups);

        $filename = null;
        if ($this->runConfiguration->hasCommandLineOption('s')) {
            $filename = $this->runConfiguration->getCommandLineOption('s');
        }

        if ($type || $filename) {
            $outputWriter->write($filename);
        }
    }

    public function setReportStatus() {
        // check for the cmd-line-option 'v' which defines the report-status
        if ($this->runConfiguration->hasCommandLineOption('v')) {
            $this->reportStatus = 1;
        } else if ($this->runConfiguration->hasCommandLineOption('vv')) {
            $this->reportStatus = 2;
        } else if ($this->runConfiguration->hasCommandLineOption('vvv')) {
            $this->reportStatus = 3;
        }

        //Set the default for runs from 'make test'
        if ( file_exists(getcwd() . "/sapi/cli/php")) {
            $this->reportStatus = 1;
        }
    }

    public function extractResults($groupResult) {
        $groupSummary = array();
        foreach($groupResult as $testResult) {
            $groupSummary[$testResult->getName()] = $testResult->getStatus();
            if($testResult->getStatus() == 'redirected') {
                $this->redirectedTestCases[] = $testResult->getRedirectedTestCase();
            }
        }
        return $groupSummary;
    }
    /*
     * This, invoked by using the -g command line option,
     * makes an attempt to distribute tests evenly across the processors
     * based on 'weightings'. The weightings are actually just timings from a previous
     * parallel run.
     */
    public function groupTasksByWeight($testDirectories, $groupConfigurations) {

        //First set weights.
        $weightedDirectoryList = array();
        $processorWeightSum = array();
        $taskListByProcessor = array();

        foreach($testDirectories as $subDir) {
            $key = rtUtil::stripPath($subDir);
            if($this->runConfiguration->hasWeight($key)) {
                $weightedDirectoryList[$subDir] = $this->runConfiguration->getWeight($key);
            } else {
                $weightedDirectoryList[$subDir] = 1;
            }
        }

        //Order subditrectories by decreasing weight.
        arsort($weightedDirectoryList, SORT_NUMERIC);

        //Assign the first n tasks across n processors. Having ordered the tasls these will be the longest running tasks
        for($i=0; $i<$this->processorCount; $i++) {
            list($key, $value) = each($weightedDirectoryList);
            $processorWeightSum[$i] =$value;
            $task = new rtTaskTestGroup($this->runConfiguration, $key, $groupConfigurations[$key]);
            $taskListByProcessor[$i] = array($task);
        }

        //Continue to assign tasks to processors based on an estimate of how long each one will take.
        for ($i=$this->processorCount; $i<count($weightedDirectoryList); $i++) {
            list($key, $value) = each($weightedDirectoryList);
            $procID = rtUtil::getMin($processorWeightSum);
            $processorWeightSum[$procID] += $value;
            $task = new rtTaskTestGroup($this->runConfiguration, $key, $groupConfigurations[$key]);
            array_push($taskListByProcessor[$procID], $task);
        }

        //Reverse the order of even numbered lists so that not all processors are runnung big tasks at the same time
        //This seems to work better - possibly the longer running tasks are resource constrined by something other than CPU?
        for($i=0; $i<$this->processorCount; $i+=2) {
            $taskListByProcessor[$procID] = array_reverse($taskListByProcessor[$procID]);
        }

        return $taskListByProcessor;
    }
}
?>
