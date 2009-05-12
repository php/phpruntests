<?php

require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__) . '../../../src/rtAutoload.php';

class rtRuntestsConfigurationTest extends PHPUnit_Framework_TestCase
{
    
    public function setUp() {
        $this->php = trim(shell_exec("which php"));
    }
    public function testCreateUnix()
    {
        $config = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', $this->php, 'test.phpt'));
        $config->configure();
        
        $testFiles = $config->getSetting('rtTestFileSetting');

        $this->assertEquals('/usr/local/bin/php', $config->getSetting('rtPhpExecutableSetting'));
        $this->assertEquals('test.phpt', $testFiles[0]);
    }  

    public function testCreateWin()
    {
        $config = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', $this->php, 'test.phpt'), 'Windows');
        $config->configure();
        
        $testFiles = $config->getSetting('rtTestFileSetting');

        $this->assertEquals('/usr/local/bin/php', $config->getSetting('rtPhpExecutableSetting'));
        $this->assertEquals('test.phpt', $testFiles[0]);
    } 
}
?>
