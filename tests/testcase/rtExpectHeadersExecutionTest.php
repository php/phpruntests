<?php

require_once __DIR__ . '/../rtTestBootstrap.php';

class rtExpectHeadersExecutionTest extends PHPUnit_Framework_TestCase
{
    private $path_to_tests;
    private $sample_test;
    private $sample_fail;

    public function setUp()
    {
        $this->path_to_tests = realpath(dirname(__FILE__) . '/../../phpt-tests');
        $this->sample_test = $this->path_to_tests . '/sample_expectheaders.phpt';
        $this->sample_test_fail = $this->path_to_tests . '/sample_expectheaders_tofail.phpt';
    }

    public function tearDown()
    {
        @unlink($this->path-to_tests . '/sample_expectheaders.php');
        @unlink($this->path-to_tests . '/sample_expectheaders_tofail.php');
    }
    
    public function testFileRun()
    {
        //Create a new test configuration
        $config = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', RT_PHP_PATH, $this->sample_test));
        $config->setEnvironmentVariable('TEST_PHP_CGI_EXECUTABLE', RT_PHP_CGI_PATH);
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
      
        $this->assertFalse($testCase->getStatus()->getValue('fail_headers'));
    }
    
    public function testForFail()
    {
        //Create a new test configuration
        $config = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', RT_PHP_PATH, $this->sample_test_fail));
        $config->setEnvironmentVariable('TEST_PHP_CGI_EXECUTABLE', RT_PHP_CGI_PATH);
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
      
        $this->assertTrue($testCase->getStatus()->getValue('fail_headers'));
    }
}
?>
