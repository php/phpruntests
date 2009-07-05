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
    private $commandLineArguments;

    public function __construct($argv)
    {
        $this->commandLineArguments = $argv;
    }

    public function run()
    {
        //Set SSH variables

        //Configure the test environment
        $runConfiguration = rtRuntestsConfiguration::getInstance($this->commandLineArguments);
        $runConfiguration->getUserEnvironment();
        $runConfiguration->configure();
        
        //Check help message
        if($runConfiguration->hasCommandLineOption('help') || $runConfiguration->hasCommandLineOption('h')) {
            echo rtText::get('help');
            exit;
        }

        //Check the preconditions
        $preConditionList = rtPreConditionList::getInstance();

        // $preConditionList->check($this->commandLine, $this->environmentVariables);
        $preConditionList->check($runConfiguration);
        

        if ($runConfiguration->getSetting('TestDirectories') != null) {

        	// make a list of subdirectories which contain tests, includes the top level directory
        	$subDirectories = array();
            foreach ($runConfiguration->getSetting('TestDirectories') as $testDirectory) {
            	$subDirectories = array_merge($subDirectories, rtUtil::parseDir($testDirectory));
            }
            
            // check for the cmd-line-option 'z' which defines parellel-execution
            $processCount = 0;
            if ($runConfiguration->hasCommandLineOption('z')) {
            	
            	$processCount = $runConfiguration->getCommandLineOption('z');
            	
            	if (!is_numeric($processCount) || $processCount < 0) {
            		$processCount = 2;
            	}
            }

            // check for the cmd-line-option 'g' which defines the report-status
            $reportStatus = 0;
            if ($runConfiguration->hasCommandLineOption('g')) {
            	
            	$reportStatus = $runConfiguration->getCommandLineOption('g');
            	
            	if (!is_numeric($reportStatus) || $processCount < 0) {
            		$reportStatus = 1;
            	}
            }
            	
            // create the task-list
            $taskList = array();
            foreach ($subDirectories as $subDirectory) {
            	$taskList[] = new rtTaskTestGroup($runConfiguration, $subDirectory);
            }
                
            // run the task-scheduler	
            $scheduler = rtTaskScheduler::getInstance();
            $scheduler->setTaskList($taskList);
            $scheduler->setProcessCount($processCount);
            $scheduler->setReportStatus($reportStatus);
			$scheduler->run();
			
			$resultList = $scheduler->getResultList();
			
			// create output
			$type = null;
        	if ($runConfiguration->hasCommandLineOption('o')) {
            	$type = $runConfiguration->getCommandLineOption('o');
	        }
			
			$outputWriter = rtTestOutputWriter::getInstance($type);
			$outputWriter->setResultList($resultList);
			$outputWriter->printOverview(sizeof($taskList), $scheduler->getProcessCount());
			
        	if ($runConfiguration->hasCommandLineOption('o')) {
	            $outputWriter->write();	
            }

        } else {

            if ($runConfiguration->getSetting('TestFiles') == null) {
                echo rtText::get('invalidTestFileName');
                exit();
            } else {
                foreach ($runConfiguration->getSetting('TestFiles') as $testName) {

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
                        $testCase = new rtPhpTest($testFile->getContents(), $testFile->getTestName(), $testFile->getSectionHeadings(), $runConfiguration, $testStatus);
                         
                        //Setup and set the local environment for the test case
                        $testCase->executeTest($runConfiguration);

                        $results = new rtTestResults($testCase);
                        $results->processResults($testCase, $runConfiguration);

                    } else {
                        $testStatus->setTrue('bork');
                        $testStatus->setMessage('bork', $testFile->getExitMessage());
                        $results = new rtTestResults(null, $testStatus);
                    }

                    rtTestOutputWriter::flushResult(array($results), 3);
                }
            }
        }
    }
}
?>
