<?php

require_once dirname(__FILE__) . '../../../src/rtAutoload.php';
require_once dirname(__FILE__) . '/../rtTestBootstrap.php';

class rtDeflatePostExecutionTest extends PHPUnit_Framework_TestCase
{
    private $sample_test;

    public function setUp()
    {
    	 if(ZLIB == 0) {
        	$this->markTestSkipped(
              'The zlib extension is not available.'
            );
        } else {
        	$this->path_to_tests = realpath(dirname(__FILE__) . '/../../phpt-tests');
        	$this->sample_test = $this->path_to_tests . '/sample_deflatepost.phpt';
        }
    }

    public function tearDown()
    {
        @unlink($this->path_to_tests . '/sample_deflatepost.php');
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
        $output = $testCase->getOutput();
        
       
        $this->assertEquals('It worked!', trim($output));
        $this->assertFalse($testCase->getStatus()->getValue('fail'));
        
        

    }
}  
?>
