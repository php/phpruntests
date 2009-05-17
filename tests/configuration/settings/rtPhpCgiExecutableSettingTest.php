<?php

require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__) . '../../../../src/rtAutoload.php';

class rtPhpCgiExecutableSettingTest extends PHPUnit_Framework_TestCase
{
    public function testSetPhpCgiExecutableEV() {
        $configuration = rtRuntestsConfiguration::getInstance(array('run-tests.php', 'test.phpt'));
        $configuration->setEnvironmentVariable('TEST_PHP_CGI_EXECUTABLE', 'a-php-executable');
        $setting = new rtPhpCgiExecutableSetting($configuration);

        $this->assertEquals('a-php-executable', $setting->get());
    }

    public function testSetPhpCgiExecutableEVAuto() {
        $configuration = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', 'a-php-exe', 'test.phpt'));
        $configuration->setEnvironmentVariable('TEST_PHP_CGI_EXECUTABLE', 'auto');
        $setting = new rtPhpCgiExecutableSetting($configuration);

        $this->assertEquals(realpath(getcwd()).'/sapi/cgi/php-cgi', $setting->get('PhpCgiExecutable'));
    }

    public function testSetPhpCgiExecutableNotSet() {
        $configuration = rtRuntestsConfiguration::getInstance(array('run-tests.php', 'test.phpt'));
        $configuration->setEnvironmentVariable('TEST_PHP_CGI_EXECUTABLE', '');
        $setting = new rtPhpCgiExecutableSetting($configuration);

        $this->assertEquals(null, $setting->get());
    }

    public function testSetFromCliExecutableName() {
        $configuration = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', '/a/sapi/cli/php', 'test.phpt'));
        $configuration->setEnvironmentVariable('TEST_PHP_CGI_EXECUTABLE', null);
        $setting = new rtPhpCgiExecutableSetting($configuration);

        $this->assertEquals('/a/sapi/cgi/php-cgi', $setting->get());
    }
    public function testSetFromCli2() {
        $configuration = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', '/a/sapi/cli/php', 'test.phpt'));
        $setting = new rtPhpCgiExecutableSetting($configuration);

        $this->assertEquals('/a/sapi/cgi/php-cgi', $setting->get());
    }
}
?>
