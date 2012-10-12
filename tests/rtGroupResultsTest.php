<?php
require_once dirname(__FILE__) . '/../src/rtAutoload.php';
require_once dirname(__FILE__) . '/rtTestBootstrap.php';

class rtGroupResultsTest extends PHPUnit_Framework_TestCase
{
	
	public function setUp()
    {
        $this->testCase = array (
                            '--TEST--', 
                            'This is a test',
                            '--FILE--',
                            '<?php',
                            ' echo "hello world"; ',
                            '?>',
                            '===Done===',
                            'blah blah blah',
                            '--EXPECTF--',
                            'hello world',
                            '===Done===',
                            'gah',
        );
    }
	public function testCreateInstance()
    {
	   
    	$results = new rtGroupResults("some_directory");   	
        $this->assertEquals($results->getGroupName(), "some_directory");
    }
    
    public function testTestResult()
    {
	   
    	$results = new rtGroupResults("some_directory"); 
    	$results->setTestStatus('test_name', 'test_status');
    	
    	$resultsArray = $results->getTestStatusList();
    		
        $this->assertEquals($resultsArray['test_name'], "test_status");
    }
    
	public function testRedirect()
    {
    	$config = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', RT_PHP_PATH, 'test.phpt'));
        $config->configure();

        $status = new rtTestStatus('nameOfTest');
        $testcase = new rtPhpTest($this->testCase, 'nameOfTest', array('TEST', 'FILE', 'EXPECTF'), $config, $status);
    	
	    
    	$results = new rtGroupResults("some_directory"); 
    	$results->setRedirectedTestCase($testcase);
    	
    	$redirectsArray = $results->getRedirectedTestCases();
    		
        $this->assertEquals($redirectsArray[0], $testcase);
    }
    
    
}