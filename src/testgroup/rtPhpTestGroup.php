<?php
/**
 * rtPhpTestGroup
 *
 * Runs a 'group of tests'. A 'group' is all or the tests in a single directory.
 *
 * @category   Testing
 * @package    RUNTESTS
 * @author     Zoe Slattery <zoe@php.net>
 * @author     Stefan Priebsch <spriebsch@php.net>
 * @copyright  2009 The PHP Group
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 *
 */
class rtPhpTestGroup extends rtTask implements rtTaskInterface
{
    protected $testDirectory;
    protected $testCases;
    protected $runConfiguration;
    protected $groupConfiguration;
    protected $groupResults;


    public function __construct(rtRuntestsConfiguration $runConfiguration, $directory, $groupConfiguration)
    {
        $this->runConfiguration = $runConfiguration;
        $this->testDirectory = $directory;
        $this->groupConfiguration = $groupConfiguration;
        $this->groupResults = new rtGroupResults($directory);
        $this->init();
    }

    public function __destruct() {
        unset ($this->testCases);
        unset ($this->groupResults);
         
    }


    public function init()
    {
        if($this->groupConfiguration->isRedirect()) {
            //merge in environmental variables (this is for REDEIRRECT).
            foreach($this->groupConfiguration->getEnvironmentVariables() as $key=>$value) {
                $this->runConfiguration->setEnvironmentVariable($key, $value);
            }
        }
         
        if($this->groupConfiguration->hasSkipCode()) {            	
            //If there is some 'skip' code run it to see if the tests should be skipped and then do nothing else

            $phpCommand = $this->runConfiguration->getSetting('PhpExecutable');
            $arguments = preg_replace('/error_reporting=32767/', 'error_reporting=0', $this->runConfiguration->getSetting('PhpCommandLineArguments'));

            $phpCommand .= ' -f '.$this->groupConfiguration->getSkipFile();
             
            $runner = new rtPhpRunner($phpCommand);
            $result = $runner->runphp();
             
            if (preg_match('/^\s*skip\s*(.+)\s*/i', $result, $matches)) {
                $this->groupResults->setSkip(true);
                $this->groupResults->setAbsTime(microtime(true));
                $this->groupResults->setTime(0);

            }
        }
        

        if($this->isSkipGroup() !== true) {
         
            $this->testFiles = rtUtil::getTestList($this->testDirectory);

            $redirectFromID = $this->groupConfiguration->getRedirectFromID();

            foreach ($this->testFiles as $testFileName) {

                if (!file_exists($testFileName)) {
                    echo rtText::get('invalidTestFileName', array($testFileName));
                    exit();
                }

                // Create a new test file object;
                $testFile = new rtPhpTestFile();
                $testFile->doRead($testFileName);
                $testFile->normaliseLineEndings();

                //The test name is the full path to the test file name without the .phpt

                $testStatus = new rtTestStatus($testFile->getTestName());
                if ($testFile->arePreconditionsMet() ) {
                    // Create a new test case
                    $this->testCases[] = new rtPhpTest($testFile->getContents(), $testFile->getTestName(), $testFile->getSectionHeadings(), $this->runConfiguration, $testStatus, $redirectFromID);
                } elseif (in_array("REDIRECTTEST",$testFile->getSectionHeadings())){
                    //Redirect handler, save the test case for processing after the main groups have finished.
                    //Check to make sure that it shouldn't be skipped, if skipped don't save it
                    $redirectedTest= new rtPhpTest($testFile->getContents(), $testFile->getTestName(), $testFile->getSectionHeadings(), $this->runConfiguration, $testStatus);
                    if($redirectedTest->hasSection('SKIPIF')) {
                        $redirectedTest->runSkipif($this->runConfiguration);
                        if($redirectedTest->getStatus()->getValue('skip')) {
                            $testStatus->setTrue('skip');
                            $testStatus->setMessage('skip', $testFile->getExitMessage(). ' and the skip condition has failed');
                            $this->groupResults->setTestStatus($testFile->getTestName(), $testStatus);
                        } else {
                            $testStatus->setTrue('redirected');
                            $testStatus->setMessage('redirected', $testFile->getExitMessage());
                            $this->groupResults->setTestStatus($testFile->getTestName(), $testStatus);
                            $this->groupResults->setRedirectedTestCase($redirectedTest);
                        }

                    }
                 
                } else {
                    $testStatus->setTrue('bork');
                    $testStatus->setMessage('bork', $testFile->getExitMessage());
                    $this->groupResults->setTestStatus($testFile->getTestName(), $testStatus);
                 
                }
          
            }
        }
    }

    public function run()
    {
        $s=microtime(true);
         
        if (count($this->testCases) == 0) {
            $e=microtime(true);
            $this->groupResults->setTime($e-$s);
            $this->groupResults->setAbsTime($e);            
            return;
        }

        for($i=0; $i<count($this->testCases); $i++) {

            $testCase = $this->testCases[$i];

            $testCase->executeTest($this->runConfiguration);


             
            $testResult = new rtTestResults($testCase);
            $testResult->processResults($testCase, $this->runConfiguration);
            $this->groupResults->setTestStatus($testCase->getName(), $testResult->getStatus());

             
        }

        $e=microtime(true);

        $this->groupResults->setTime($e-$s);
        $this->groupResults->setAbsTime($e);
     
    }

    public function writeGroup($outType, $cid=null)
    {
        $testOutputWriter = rtTestOutputWriter::getInstance($this->groupResults->getTestStatusList(), $outType);
        $testOutputWriter->write($this->testDirectory, $cid);
    }


    public function getTestCases() {
        return $this->testCases;
    }
     
    public function getGroupName() {
        return $this->testDirectory;
    }

    public function getGroupResults() {
        return $this->groupResults;
    }

    public function isSkipGroup() {
        return $this->groupResults->isSkipGroup();
    }

}
?>
