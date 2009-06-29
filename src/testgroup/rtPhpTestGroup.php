<?php
/**
 * rtTestGroup
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
class rtPhpTestGroup
{
    private $testDirectory;
    private $testCases;
    private $results;

    public function __construct(rtRuntestsConfiguration $runConfiguration, $directory)
    {
        $this->testDirectory = $directory;
        $this->init($runConfiguration);
    }

    public function init(rtRuntestsConfiguration $runConfiguration)
    {
        $this->testFiles = rtUtil::getTestList($this->testDirectory);

        foreach ($this->testFiles as $testFileName) {
       
            //testFiles is a list of file namnes relative to the current working directory

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
               
                $this->testCases[] = new rtPhpTest($testFile->getContents(), $testFile->getTestName(), $testFile->getSectionHeadings(), $runConfiguration, $testStatus);
            } else {
                $testStatus->setTrue('bork');
                $testStatus->setMessage('bork', $testFile->getExitMessage());
                $this->results[] = new rtTestResults(null, $testStatus);
            }
        }
    }

    public function runGroup(rtRuntestsConfiguration $runConfiguration)
    {
        foreach ($this->testCases as $testCase) {

            $testCase->executeTest($runConfiguration);
             
            $testResult = new rtTestResults($testCase);
            $testResult->processResults($testCase, $runConfiguration);
            $this->results[] = $testResult;
        }
    }

    public function writeGroup($outType, $cid=null)
    {
        $testOutputWriter = rtTestOutputWriter::getInstance($this->results, $outType);
        $testOutputWriter->write($this->testDirectory, $cid);
    }
    
    
    public function getResults()
    {
    	return $this->results;
    }

}
?>
