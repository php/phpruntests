<?php

require_once dirname(__FILE__) . '../../../src/rtAutoload.php';
require_once dirname(__FILE__) . '/../rtTestBootstrap.php';

class rtGetExecutionTest extends PHPUnit_Framework_TestCase
{
    private $path_to_tests;
    private $sample_test;
    private $sample_fail;

    public function setUp()
    {

        $this->path_to_tests = realpath(dirname(__FILE__) . '/../../phpt-tests');
        $this->sample_test = $this->path_to_tests . '/sample_get.phpt';
    }

    public function tearDown()
    {
        @unlink($this->path-to_tests . '/sample_get.php');
    }

    public function testFileRun()
    {
        //Create a new test configuration
        $config = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', RT_PHP_PATH, $this->sample_test));
         //Need to get rid of xdebug in these tests, reformats the output so they fail.
        $config->setEnvironmentVariable('TEST_PHP_CGI_EXECUTABLE', RT_PHP_CGI_PATH." -d xdebug.default_enable=0");
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
        $status = $testCase->getStatus();
         
        $this->assertEquals('85', strlen($output));
        $this->assertFalse($testCase->getStatus()->getValue('fail'));


    }

    public function testNoCGI()
    {
        //Create a new test configuration
        $config = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', RT_PHP_PATH, $this->sample_test));
        $config->setEnvironmentVariable('TEST_PHP_CGI_EXECUTABLE', null);
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
        //var_dump($output);

        $status = $testCase->getStatus();
         
         
        $setPhp = $config->getSetting('PhpExecutable');


        if   (substr($setPhp, -3) === "php") {
            // Make no assertion bacuse the CGI executable can be guesed
        } else {
            $this->assertEquals(0, strlen($output));
            $this->assertTrue($testCase->getStatus()->getValue('skip'));
            $this->assertEquals('The CGI executable is unavailable', $testCase->getStatus()->getMessage('skip'));
        }



    }
}
?>
