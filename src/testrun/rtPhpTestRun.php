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
	    
	    if(($this->numberOfSerialGroups != 0) || ($this->numberOfParallelGroups != 0))	{
	    	$this->createRunOutput();
	    }
	    
	}
	
	public function doGroupRuns() {
		
		$subDirectories = $this->buildSubDirectoryList($this->runConfiguration->getSetting('TestDirectories'));
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
					if($this->numberOfSerialGroups > 0)	{			
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
		 
		// create the task-list
		$taskList = array();
		foreach($testDirectories as $testGroup) {
			$taskList[] = new rtTaskTestGroup($this->runConfiguration, $testGroup, $groupConfigurations[$testGroup]);	
		}

		// run the task-scheduler
		$scheduler = rtTaskScheduler::getInstance();
		$scheduler->setTaskList($taskList);
		$scheduler->setProcessCount($processCount);
		$scheduler->setReportStatus($this->reportStatus);
		$scheduler->run();

		foreach($scheduler->getResultList() as $groupResult) {
			
		$this->resultList[] = $groupResult->getTestStatusList();
		
		// Debug - get which group was run by which processor and how long each took
		//
		
		if($this->runConfiguration->hasCommandLineOption('debug')) {
		$time = round($groupResult->getTime(), 2);
		
		$absTime = $groupResult->getAbsTime() - $this->runStartTime;
		
		$absTime = round($absTime, 2);
		
		echo "\nPARDBG," . $absTime. "," . $time . "," . $groupResult->getProcessorId() . "," . $groupResult->getRunOrder() . "," . $groupResult->getGroupName();

		}
		
		$redirects = $groupResult->getRedirectedTestCases();
        	foreach($redirects as $testCase) {
        		$this->redirectedTestCases[] = $testCase;
        	}
		}
		
		
			 		 
	}
	
	public function run_serial_groups($testDirectories, $groupConfigurations) {
		
		$count = 0;
		
	
		//xdebug_start_trace('/tmp/memorycheck');
		
		foreach($testDirectories as $subDirectory) {
			
		
			
		  
		    // Memory usage debugging
		    //$startm = memory_get_usage();
		     
		    
			$testGroup = new rtPhpTestGroup($this->runConfiguration, $subDirectory, $groupConfigurations[$subDirectory]);
			$testGroup->run();
			
			// Memory usage debugging			
			//$midm = memory_get_usage();

			
			rtTestOutputWriter::flushResult($testGroup->getGroupResults()->getTestStatusList(), $this->reportStatus);			
        	$this->resultList[] = $testGroup->getGroupResults()->getTestStatusList();
        	
		    if($this->runConfiguration->hasCommandLineOption('debug')) {
		    	
				$time = round($testGroup->getGroupResults()->getTime(), 2);
								
				$absTime = ($testGroup->getGroupResults()->getAbsTime()) - $this->runStartTime;				
				$absTime = round($absTime, 2);
				
		
				echo "\nSERDBG," . $absTime . "," . $time . "," . $testGroup->getGroupResults()->getProcessorId() . "," . $count . "," . $testGroup->getGroupResults()->getGroupName();

			}
        	
        	// Memory usage debugging
        	//$midm2 = memory_get_usage();
        	
        	$redirects = $testGroup->getGroupResults()->getRedirectedTestCases();
        	foreach($redirects as $testCase) {
        		$this->redirectedTestCases[] = $testCase;
        	}
        	
        	// Memory usage debugging
        	//$midm3 = memory_get_usage();
        	
			
        	$testGroup->__destruct();
        	unset($testGroup);
        	
        	// Memory usage debugging
        	//echo "\n" . $startm . ", " . $midm. ", " .$midm2. ", " .$midm3. ", " .memory_get_usage() . ", ". $subDirectory . "\n";
        $count++;				
		}
		
		//xdebug_stop_trace();			
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
			$groupConfig->parse();
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
		
				$outputWriter->printOverview($this->numberOfParallelGroups, $this->numberOfSerialGroups, $this->processorCount);

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
	
}
?>
