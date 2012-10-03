<?php
require_once dirname(__FILE__) . '/../src/rtAutoload.php';
require_once dirname(__FILE__) . '/rtTestBootstrap.php';

class rtGroupConfigurationTest extends PHPUnit_Framework_TestCase
{
	public function testCreateInstance()
    {
	    //$directory = realpath(dirname(__FILE__) . '/../phpt-tests');
    	$config = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', RT_PHP_PATH, 'testgroup'));
    	
        $config->configure();    	
    }
}