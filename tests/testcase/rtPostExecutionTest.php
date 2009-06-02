<?php

require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__) . '../../../src/rtAutoload.php';

class rtPostExecutionTest extends PHPUnit_Framework_TestCase
{
    private $sample_test;

    public function setUp()
    {
        $this->php = trim(shell_exec("which php"));
        $this->php_cgi = trim(shell_exec("which php-cgi"));

        $this->path_to_tests = realpath(dirname(__FILE__) . '/../../phpt-tests');
        $this->sample_test = $this->path_to_tests . '/sample_post.phpt';
    }

    public function tearDown()
    {
        @unlink($this->path-to_tests . '/sample_post.php');
    }

    public function testFileRun()
    { 
        //Create a new test configuration
        $config = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', $this->php, $this->sample_test));
        $config->setEnvironmentVariable('TEST_PHP_CGI_EXECUTABLE',$this->php_cgi);
        $config->configure();

        //Retrieve the array of test file names
        $testFiles = $config->getSetting('TestFiles');

        //Read the test file
        $testFile = new rtPhpTestFile();
        $testFile->doRead($testFiles[0]);
        $testFile->normaliseLineEndings();

        //Create a new test case
        $status = new rtTestStatus();
        $testCase = new rtPhpTest($testFile->getContents(), $testFile->getTestName(), $testFile->getSectionHeadings(), $config, $status);      

        //Setup and set the local environment for the test case
        $testCase->executeTest($config);
        $output = $testCase->getOutput();
        $status = $testCase->getStatus();
        
        $this->assertEquals('85', strlen($output));
        $this->assertEquals('', $status['pass']);
        

    }
}
?>
