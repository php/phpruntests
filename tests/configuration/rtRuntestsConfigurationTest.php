<?php

require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__) . '../../../src/rtAutoload.php';

class rtRuntestsConfigurationTest extends PHPUnit_Framework_TestCase
{
    public function testCreateUnix()
    {
        $config = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', 'a-php-exe', 'test.phpt'));
        $config->configure();
        
        $testFiles = $config->getSetting('TestFiles');

        $this->assertEquals('a-php-exe', $config->getSetting('PhpExecutable'));
        $this->assertEquals('test.phpt', $testFiles[0]);
    }  

    public function testCreateWin()
    {
        $config = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', 'a-php-exe', 'test.phpt'),'Windows');
        $config->configure();
        
        $testFiles = $config->getSetting('TestFiles');

        $this->assertEquals('a-php-exe', $config->getSetting('PhpExecutable'));
        $this->assertEquals('test.phpt', $testFiles[0]);
    } 
}
?>
