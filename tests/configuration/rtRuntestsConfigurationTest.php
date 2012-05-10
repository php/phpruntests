<?php

require_once dirname(__FILE__) . '../../../src/rtAutoload.php';
require_once dirname(__FILE__) . '/../rtTestBootstrap.php';

class rtRuntestsConfigurationTest extends PHPUnit_Framework_TestCase
{
    
    public function setUp() {
    }
    
    public function testCreateUnix()
    {
        $config = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', RT_PHP_PATH, 'test.phpt'));
        $config->configure();
        
        $testFiles = $config->getSetting('TestFiles');

        $this->assertEquals(RT_PHP_PATH, $config->getSetting('PhpExecutable'));
        $this->assertEquals('test.phpt', $testFiles[0]);
    }  

    public function testCreateWin()
    {
        $config = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', RT_PHP_PATH, 'test.phpt'), 'Windows');
        $config->configure();
        
        $testFiles = $config->getSetting('TestFiles');

        $this->assertEquals(RT_PHP_PATH, $config->getSetting('PhpExecutable'));
        $this->assertEquals('test.phpt', $testFiles[0]);
    } 
}
?>
