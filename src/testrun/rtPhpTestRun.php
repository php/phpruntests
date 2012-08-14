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

	public function __construct($argv)
	{
		$this->commandLineArguments = $argv;
	}

	public function run()
	{
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

        // Main decision point. Either we start this with a directory (or set of directories, in which case tests are 
        // run as a group (and in parallel if required) or......
        //
		if ($this->runConfiguration->getSetting('TestDirectories') != null) {

			$this->run_group($this->runConfiguration->getSetting('TestDirectories'));

		} else {
	    //.. the input is a test file, or list of files and are just run as single tests
	    // and not in parallel

			if ($this->runConfiguration->getSetting('TestFiles') == null) {
				echo rtText::get('invalidTestFileName');
				exit();
			}else{
				$this->run_tests($this->runConfiguration->getSetting('TestFiles'));
			}
		}

	    if(count($this->redirectedTestCases) > 0) {
	    	foreach($this->redirectedTestCases as $testCase){
	    		echo $testCase->getName() . "\n";
	    		
	    		
	    	}
	    	
            //For each test case - construct a new group
            //Call run_group() again with an array of groups
            //
            // The redirect section has PHP code in it but no tags.
            // It is code the needs to be run as part of run-tests, not as a 'runnable' section -
            // eek it's eval(). Is there any better way to do this? It's setting a 'group 
            // configuration' which we don't have at the moment - so maybe we need one?
            // re-implementing a differnt way would be nice. Just reading the config and not eval()ing it
            //which seems unnecessary.
            //
            // for now, rtRedirectedSecion is part of 'config', not part of 'executable';
	    }

	}

	public function run_group($testDirectories) {
		// make a list of subdirectories which contain tests, includes the top level directory
		 
		$subDirectories = array();
		foreach ($testDirectories as $testDirectory) {
			$subDirectories = array_merge($subDirectories, rtUtil::parseDir($testDirectory));
		}
		 
		// check for the cmd-line-option 'z' which defines parellel-execution
		$processCount = 0;
		if ($this->runConfiguration->hasCommandLineOption('z')) {
			 
			$processCount = $this->runConfiguration->getCommandLineOption('z');
			 
			if (!is_numeric($processCount) || $processCount < 0) {
				$processCount = 2;
			}
		}


		// check for the cmd-line-option 'v' which defines the report-status
		$reportStatus = 0;
		if ($this->runConfiguration->hasCommandLineOption('v')) {
			$reportStatus = 1;
		} else if ($this->runConfiguration->hasCommandLineOption('vv')) {
			$reportStatus = 2;
		} else if ($this->runConfiguration->hasCommandLineOption('vvv')) {
			$reportStatus = 3;
		}
		 
		// create the task-list
		$taskList = array();
		
		
		foreach ($subDirectories as $subDirectory) {
			$taskList[] = new rtTaskTestGroup($this->runConfiguration, $subDirectory);
		}
		

		// run the task-scheduler
		$scheduler = rtTaskScheduler::getInstance();
		$scheduler->setTaskList($taskList);
		$scheduler->setProcessCount($processCount);
		$scheduler->setReportStatus($reportStatus);
		$scheduler->run();
			
		$resultList = $scheduler->getResultList();
		
		//locate any redirected tests in teh group results files.
	    foreach ($resultList as $testGroupResults) {
        	
        	foreach ($testGroupResults as $testResult) {
    	 	 
        		if($testResult->getStatus() == 'redirected') {
        			$this->redirectedTestCases[] = $testResult->getRedirectedTestCase();
        		}				
	    	}
    	}
    	
			
		// create output
		$type = null;
		if ($this->runConfiguration->hasCommandLineOption('o')) {
			$type = $this->runConfiguration->getCommandLineOption('o');
		}
			
		$outputWriter = rtTestOutputWriter::getInstance($type);
		$outputWriter->setResultList($resultList);
		$outputWriter->printOverview(sizeof($taskList), $scheduler->getProcessCount());

		$filename = null;
		if ($this->runConfiguration->hasCommandLineOption('s')) {
			$filename = $this->runConfiguration->getCommandLineOption('s');
		}
			
		if ($type || $filename) {
			$outputWriter->write($filename);
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

			} elseif (in_array("REDIRECTTEST", $testFile->getSectionHeadings())) {
				 
				//Redirect handler
				//Build a list of redirected test cases
				 
				//TODO: need to run the skipif section here..
				$this->redirectedTestCases[] = new rtPhpTest($testFile->getContents(), $testFile->getTestName(), $testFile->getSectionHeadings(), $this->runConfiguration, $testStatus);
				 
				$testStatus->setTrue('redirected');
				$testStatus->setMessage('redirected', $testFile->getExitMessage());
				$results = new rtTestResults(null, $testStatus);
			} else {
				$testStatus->setTrue('bork');
				$testStatus->setMessage('bork', $testFile->getExitMessage());
				$results = new rtTestResults(null, $testStatus);
			}

			rtTestOutputWriter::flushResult(array($results), 3);
		}
		 		 
	}
}
?>
