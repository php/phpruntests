<?php

require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__) . '../../../../src/rtAutoload.php';

class rtPhpCgiExecutableSettingTest extends PHPUnit_Framework_TestCase
{
    public function testSetPhpCgiExecutableEV() {
        $configuration = rtRuntestsConfiguration::getInstance(array('run-tests.php', 'test.phpt'));
        $configuration->setEnvironmentVariable('TEST_PHP_CGI_EXECUTABLE', 'a-php-executable');
        $cgisetting = new rtPhpCgiExecutableSetting($configuration);
        $this->assertEquals('a-php-executable', $cgisetting->get());
    }

    public function testSetPhpCgiExecutableEVAuto() {
        $configuration = rtRuntestsConfiguration::getInstance(array('run-tests.php','-p', 'a-php-exe', 'test.phpt'));
        $configuration->setEnvironmentVariable('TEST_PHP_CGI_EXECUTABLE', 'auto');
        $configuration->configure();     
        $this->assertEquals(realpath(getcwd()).'/sapi/cgi/php', $configuration->getSetting('PhpCgiExecutable'));
    }

    public function testSetPhpCgiExecutableNotSet() {
        $configuration = rtRuntestsConfiguration::getInstance(array('run-tests.php', 'test.phpt'));
        $cgisetting = new rtPhpCgiExecutableSetting($configuration);
        $this->assertEquals(null, $cgisetting->get());
    }

    public function testSetFromCliExecutableName() {        
        $config = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', '/a/sapi/cli/a-php-exe', 'test.phpt'));
        $config->configure();
        $this->assertEquals('/a/sapi/cgi/php', $config->getSetting('PhpCgiExecutable'));
    }
}
?>
