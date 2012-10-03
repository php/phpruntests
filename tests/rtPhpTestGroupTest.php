<?php
require_once dirname(__FILE__) . '/../src/rtAutoload.php';
require_once dirname(__FILE__) . '/rtTestBootstrap.php';

class rtPHpTestGroupTest extends PHPUnit_Framework_TestCase
{
    
    public function setUp() {
    }
    
    public function testCreateInstance()
    {
    	$directory = realpath(dirname(__FILE__) . '/../phpt-tests');
    	$config = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', RT_PHP_PATH, $directory));
    	
        $config->configure(); 

        $gConf = new rtGroupConfiguration('wibble');
    	
    	$phpTestGroup = new rtPhpTestGroup($config, $directory, $gConf);
    
    	
    	$validTestCaseCount = count($phpTestGroup->getTestCases());
    	$phptFileCount = count(glob($directory . "/*.phpt"));
    	$inValidTestCaseCount = count($phpTestGroup->getResult());
       
        //PhpTestGroup should divide the test cases into valid tests (TestCases),
        //or invalid ones. An invalid test is either one which 'borks' (that is, the
        //phpt fails to parse), or a redirected test case. Invalid test cases are not run
        //but a TestResults object is created during initialisation of the TestGroup.
    	
    	$this->assertEquals($phptFileCount, $validTestCaseCount + $inValidTestCaseCount);
    
    }
    
    public function testFindRedirect()
    {
    	$directory = realpath(dirname(__FILE__) . '/../phpt-tests');
    	$config = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', RT_PHP_PATH, $directory));
    	
        $config->configure(); 
        $gConf = new rtGroupConfiguration('wibble');   	
    	
    	$phpTestGroup = new rtPhpTestGroup($config, $directory, $gConf);
    
    	
    	
    	
    	$inValidTests = $phpTestGroup->getResult();
       
        foreach($inValidTests as $testResult) {
        	if($testResult->getStatus() == 'redirected') {
        		//If the status has been set to redirected it should have a valid REDIRECTTEST section
        		$testCase = $testResult->getRedirectedTestCase();
        		$this->assertTrue($testCase->hasSection('REDIRECTTEST'));
        	}       	
        }   
    }
    
    
 
}
?>
