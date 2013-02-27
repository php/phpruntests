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
    	$inValidTestCaseCount = count($phpTestGroup->getGroupResults()->getTestStatusList());
       
        //PhpTestGroup should divide the test cases into valid tests (TestCases),
        //or invalid ones. An invalid test is either one which 'borks' (that is, the
        //phpt fails to parse), or a redirected test case. Invalid test cases are not run
        //but a TestStatus object is created and added to the group results.
    	
    	$this->assertEquals($phptFileCount, $validTestCaseCount + $inValidTestCaseCount);
    
    }
    
    public function testFindRedirect()
    {
    	$directory = realpath(dirname(__FILE__) . '/../phpt-tests');
    	$config = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', RT_PHP_PATH, $directory));
    	
        $config->configure(); 
        $gConf = new rtGroupConfiguration('wibble');   	
    	
    	$phpTestGroup = new rtPhpTestGroup($config, $directory, $gConf);
    
    	
    	
    	
    	$redirects = $phpTestGroup->getGroupResults()->getRedirectedTestCases();
       
        foreach($redirects as $testCase) {   	
        	$this->assertTrue($testCase->hasSection('REDIRECTTEST'));     	
        }   
    }
    
    public function testSkipGroup() {
        $directory = realpath(dirname(__FILE__) . '/../phpt-tests/group_of_tests');
    	$run_config = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', RT_PHP_PATH, $directory));
    	
        $run_config->configure(); 
        
        $group_config = new rtGroupConfiguration($directory);
        $group_config->parseConfiguration();   	
    	
    	$phpTestGroup = new rtPhpTestGroup($run_config, $directory, $group_config);
    	$phpTestGroup->init();  
    	  	
    	$this->assertTrue($phpTestGroup->isSkipGroup());
    }
    
 
}
?>
