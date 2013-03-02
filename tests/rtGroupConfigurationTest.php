<?php
require_once dirname(__FILE__) . '/../src/rtAutoload.php';
require_once dirname(__FILE__) . '/rtTestBootstrap.php';

class rtGroupConfigurationTest extends PHPUnit_Framework_TestCase

{
    protected $path_to_group;
    
    public function setUp() {
        $this->path_to_group = realpath(dirname(__FILE__) . '/../phpt-tests/group_of_tests/');        
    }
    
	public function testCreateInstance()
    {
    	$config = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', RT_PHP_PATH, $this->path_to_group));
    	
        $config->configure();  
        
        $groupConfig = new rtGroupConfiguration($this->path_to_group);
        
        $groupConfig->parseConfiguration();
        
        $this->assertTrue(file_exists($groupConfig->getSkipFile())); 
        $this->assertTrue($groupConfig->hasSkipCode());        
        
    }
}