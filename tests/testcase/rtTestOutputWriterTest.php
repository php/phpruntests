<?php

require_once dirname(__FILE__) . '../../../src/rtAutoload.php';

class rtTestOutputWriterTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->path_to_tests = realpath(dirname(__FILE__) . '/../../phpt-tests');
        $this->sample_test = $this->path_to_tests . '/sample_test.phpt';
        $this->sample_test_fail = $this->path_to_tests . '/sample_test_fail.phpt';
        $this->sample_skipif = $this->path_to_tests . '/sample_skipif.phpt';
    }

    public function tearDown()
    {
        @unlink($this->sample_test = $this->path_to_tests . '/sample_test.php');
        @unlink($this->sample_test = $this->path_to_tests . '/sample_test.out');
        @unlink($this->sample_test = $this->path_to_tests . '/sample_test.exp');
        @unlink($this->sample_test = $this->path_to_tests . '/sample_test_fail.diff');
        @unlink($this->sample_test = $this->path_to_tests . '/sample_test_fail.exp');
        @unlink($this->sample_test = $this->path_to_tests . '/sample_test_fail.out');
        @unlink($this->sample_test = $this->path_to_tests . '/sample_test_fail.php');
        @unlink($this->sample_test = $this->path_to_tests . '/sample_skipif.skipif.php');
        @unlink($this->sample_test = $this->path_to_tests . '/sample_skipif.php');
    }

    public function testFailedTest()
    {
        //Create a new test configuration
        $config = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', RT_PHP_PATH, $this->sample_test_fail));
        $config->configure();

        //Retrieve the array of test file names
        $testFiles = $config->getSetting('TestFiles');

        //Read the test file
        $testFile = new rtPhpTestFile();
        $testFile->doRead($testFiles[0]);
        $testFile->normaliseLineEndings();
 

        //Create a new test case
        $status = new rtTestStatus($testFile->getTestName());
        $testCase = new rtPhpTest($testFile->getContents(), $testFile->getTestName(), $testFile->getSectionHeadings(), $config, $status);

        //Setup and set the local environment for the test case
        $testCase->executeTest($config);

        $results = new rtTestResults($testCase);
        $results->processResults($testCase, $config);

        $testOutputWriter = rtTestOutputWriter::getInstance(array($results), 'list');

        $this->assertEquals('rtTestOutputWriterList', get_class($testOutputWriter));
    }
}
?>
