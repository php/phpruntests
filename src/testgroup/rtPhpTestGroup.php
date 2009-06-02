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

        foreach ($this->testFiles as $testName) {

            if (!file_exists($testName)) {
                echo rtText::get('invalidTestFileName', array($testName));
                exit();
            }

            // Create a new test file object;
            $testFile = new rtPhpTestFile();
            $testFile->doRead($testName);
            $testFile->normaliseLineEndings();

            $testStatus = new rtTestStatus();
            $testStatus->setTestName($testFile->getTestName());
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

    public function writeGroup()
    {
        $testOutputWriter = rtTestOutputWriter::getInstance($this->results, 'list');
        $testOutputWriter->write($this->testDirectory);
    }
}
?>
