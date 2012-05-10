<?php

require_once dirname(__FILE__) . '../../../src/rtAutoload.php';
require_once dirname(__FILE__) . '/../rtTestBootstrap.php';

class rtSkipIfExecutionTest extends PHPUnit_Framework_TestCase
{
    private $path_to_tests;

    public function setUp()
    {
        $path_to_tests = realpath(dirname(__FILE__) . '/../../phpt-tests');
        $this->sample_test = $path_to_tests . '/sample_skipif.phpt';
    }

    public function tearDown()
    {
        @unlink($this->path_to_tests. '/sample_skipif.skipif.php');
    }

    public function testFileRun()
    {
        //Create a new test configuration
        $config = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', RT_PHP_PATH, $this->sample_test));
        $config->configure();

        //Retrieve the array of test file names
        $testFiles = $config->getSetting('TestFiles');

        //Read the test file
        $testFile =new rtPhpTestFile();
        $testFile->doRead($testFiles[0]);
        $testFile->normaliseLineEndings();


        //Create a new test case
        $status = new rtTestStatus($testFile->getTestName());
        $testCase = new rtPhpTest($testFile->getContents(), $testFile->getTestName(), $testFile->getSectionHeadings(), $config, $status);      

        //Setup and set the local environment for the test case
        $testCase->executeTest($config);

        //Check that the test has been skipped
        //var_dump($testCase->getStatus());
        $this->assertTrue($testCase->getStatus()->getValue('skip'));          
    }
}
?>
