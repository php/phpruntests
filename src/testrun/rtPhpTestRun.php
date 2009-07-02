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
        if($runConfiguration->hasCommandLineOption('help')) {
            echo rtText::get('help');
            exit;
        }

        //Check the preconditions
        $preConditionList = rtPreConditionList::getInstance();

        // $preConditionList->check($this->commandLine, $this->environmentVariables);
        $preConditionList->check($runConfiguration);
        
        //Set the type of output. Defaults to 'list' - comatible with old version
        $this->outType = 'list';
        if ($runConfiguration->hasCommandLineOption('o')) {            		
            		$this->outType = $runConfiguration->getCommandLineOption('o');
        } 

        if ($runConfiguration->getSetting('TestDirectories') != null) {

            foreach ($runConfiguration->getSetting('TestDirectories') as $testDirectory) {
            	
            	// make list of subdirectories which contain tests, includes the top level directory
            	$subDirectories = rtUtil::parseDir($testDirectory);
            	
            	// check for the cmd-line-option 'z' which defines parellel-execution
            	if ($runConfiguration->hasCommandLineOption('z')) {
            		
            		$processCount = $runConfiguration->getCommandLineOption('z');
            		
            		if (!is_numeric($processCount) || $processCount <= 0) {
            			$processCount = sizeof($subDirectories);
            		}
            		
            		// create the task-list
            		$taskList = array();
	            	foreach ($subDirectories as $subDirectory) {
	            		$taskList[] = new rtTaskTestGroup($runConfiguration, $subDirectory, $this->outType);
	                }
	                
	                // start the task-scheduler for multi-processing	
	                $scheduler = rtTaskScheduler::getInstance();
	                $scheduler->setTaskList($taskList);
	                $scheduler->setProcessCount($processCount);
					$scheduler->run();
					$scheduler->printStatistic();
 
            	} else {
            		
            	    //Run tests in each subdirectory in sequence
	                foreach ($subDirectories as $subDirectory) {
	                    $testGroup = new rtPhpTestGroup($runConfiguration, $subDirectory);
	                    $testGroup->runGroup($runConfiguration);
	                    $testGroup->writeGroup($this->outType);
	                }
            		
            	}
            }

            //*have a directory or list of directories to test.
            /* if (single directory) {
            * 	if(contains sub-directories with .phpt files) {
            * 		if (parallel) {
            * 			initate parallel run
            *        } else {
            *            initaite sequential run
            *        }
            *    } else {
            *        initiate sequential run
            *    }
            * } else {    //multiple directories
            *    if (parallel) {
            *       initiate parallel (runs the list of dirs in parallel
            *    } else {
            *       run each directory in sequence
            *    }
            * }
            *
            */
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

                    $testOutputWriter = rtTestOutputWriter::getInstance(array($results), 'list');
                    $testOutputWriter->write();
                }
            }
        }
    }
}
?>
