<?php

require_once dirname(__FILE__) . '../../../src/rtAutoload.php';
require_once dirname(__FILE__) . '/../rtTestBootstrap.php';

class rtCookieExecutionTest extends PHPUnit_Framework_TestCase
{
    private $path_to_tests;
    private $sample_test;
    private $sample_fail;

    public function setUp()
    { 
        $this->path_to_tests = realpath(dirname(__FILE__) . '/../../phpt-tests');
        $this->sample_test = $this->path_to_tests . '/sample_cookie.phpt';
    }

    public function tearDown()
    {
        @unlink($this->path-to_tests . '/sample_cookie.php');
    }

    public function testFileRun()
    { 
        //Create a new test configuration
        $config = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', RT_PHP_PATH, $this->sample_test));
         //Need to get rid of xdebug in these tests, reformats the output so they fail.
        $config->setEnvironmentVariable('TEST_PHP_CGI_EXECUTABLE',RT_PHP_CGI_PATH." -n xdebug.default_enable=0");
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
       
        $this->assertFalse($testCase->getStatus()->getValue('fail'));        

    }
}  
?>
