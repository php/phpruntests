<?php

require_once dirname(__FILE__) . '../../../src/rtAutoload.php';
require_once dirname(__FILE__) . '/../rtTestBootstrap.php';

class rtTestResultsTest extends PHPUnit_Framework_TestCase
{
    public function setUp()   
    {
        $this->path_to_tests = realpath(dirname(__FILE__) . '/../../phpt-tests');
        $this->sample_test = $this->path_to_tests. '/sample_test.phpt';
        $this->sample_test_fail = $this->path_to_tests. '/sample_test_fail.phpt';
        $this->sample_skipif = $this->path_to_tests. '/sample_skipif.phpt';
        $this->sample_clean = $this->path_to_tests. '/sample_clean.phpt';
        $this->sample_bork = $this->path_to_tests. '/sample_bork.phpt';
    }

    public function tearDown()
    {
        @unlink($this->sample_test = $this->path_to_tests. '/sample_test.php');
        @unlink($this->sample_test = $this->path_to_tests. '/sample_test.out');
        @unlink($this->sample_test = $this->path_to_tests. '/sample_test.exp');
        @unlink($this->sample_test = $this->path_to_tests. '/sample_test_fail.diff');
        @unlink($this->sample_test = $this->path_to_tests. '/sample_test_fail.exp');
        @unlink($this->sample_test = $this->path_to_tests. '/sample_test_fail.out');
        @unlink($this->sample_test = $this->path_to_tests. '/sample_test_fail.php');
        @unlink($this->sample_test = $this->path_to_tests. '/sample_skipif.skipif.php');
        @unlink($this->sample_test = $this->path_to_tests. '/sample_skipif.php');
        @unlink($this->sample_test = $this->path_to_tests. '/sample_clean.php');
    }

    public function testFileDelete()
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

        $fileName = $testFile->getTestName(). ".php";

        $results = new rtTestResults($testCase);
        $results->processResults($testCase, $config);

        $this->assertFalse(file_exists($fileName));
    }

    public function testFileSaveAll()
    {
        //Create a new test configuration
        $config = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', RT_PHP_PATH, $this->sample_test, '--keep-all'));
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

        $fileName = $testFile->getTestName(). ".php";

        $results = new rtTestResults($testCase);
        $results->processResults($testCase, $config);

        $this->assertTrue(file_exists($fileName));
    }

    public function testFileSavePHP()
    {
        //Create a new test configuration
        $config = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', RT_PHP_PATH, $this->sample_test, '--keep-php'));
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

        $fileName = $testFile->getTestName(). ".php";

        $results = new rtTestResults($testCase);
        $results->processResults($testCase, $config);

        $this->assertTrue(file_exists($fileName));
    }

    public function testFailedTest()
    {
        //Create a new test configuration
        $config = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', RT_PHP_PATH, $this->sample_test_fail));
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

        $testName = $testFile->getTestName();

        $results = new rtTestResults($testCase);
        $results->processResults($testCase, $config);

        $this->assertTrue(file_exists($testName.'.diff'));
        $this->assertTrue(file_exists($testName.'.out'));
        $this->assertTrue(file_exists($testName.'.exp'));
        $this->assertTrue(file_exists($testName.'.php'));
    }

    public function testSkippedTest()
    {
        //Create a new test configuration
        $config = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', RT_PHP_PATH, $this->sample_skipif));
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

        $testName = $testFile->getTestName();

        $results = new rtTestResults($testCase);
        $results->processResults($testCase, $config);

        $this->assertFalse(file_exists($testName.'.skipif.php'));
        $this->assertFalse(file_exists($testName.'.php'));
    }

    public function testSkippedTestKeep()
    {
        //Create a new test configuration
        $config = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', RT_PHP_PATH, $this->sample_skipif, '--keep-all'));
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

        $testName = $testFile->getTestName();

        $results = new rtTestResults($testCase);
        $results->processResults($testCase, $config);

        $this->assertTrue(file_exists($testName.'.skipif.php'));
    }

    public function testCleanPass()
    {
        //Create a new test configuration
        $config = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', RT_PHP_PATH, $this->sample_clean));
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

        $testName = $testFile->getTestName();

        $results = new rtTestResults($testCase);
        $results->processResults($testCase, $config);
        
        $this->assertTrue($testCase->getStatus()->getValue('pass'));

    }

    public function testBork()
    {
        //Create a new test configuration
        $config = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', RT_PHP_PATH, $this->sample_bork));
        $config->configure();

        //Retrieve the array of test file names
        $testFiles = $config->getSetting('TestFiles');

        //Read the test file
        $testFile =new rtPhpTestFile();
        $testFile->doRead($testFiles[0]);
        $testFile->normaliseLineEndings();


        if (!$testFile->arePreConditionsMet()) {
            $testStatus = new rtTestStatus($testFile->getTestName());
            $testStatus->setTrue('bork');
            $testStatus->setMessage('bork', 'bork message');

            $results = new rtTestResults( null, $testStatus);

            $this->assertTrue($results->getStatus()->getvalue('bork'));
        }
    }
}
?>
