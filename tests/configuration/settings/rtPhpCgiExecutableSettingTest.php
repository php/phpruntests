<?php

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
        
        $setPhp = $configuration->getSetting('TEST_PHP_EXECUTABLE');
        
        if   (substr($setPhp, -3) === "php") {
            // Make no assertion because the CGI executable can be guesed
        } else {
            $this->assertEquals(null, $setting->get());
        }
    }

    public function testSetFromCliExecutableName() {
        $configuration = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', '/some/thing/php', 'test.phpt'));
        $configuration->setEnvironmentVariable('TEST_PHP_CGI_EXECUTABLE', null);
        $setting = new rtPhpCgiExecutableSetting($configuration);

        $this->assertEquals('/some/thing/php-cgi', $setting->get());
    }
    public function testSetFromCli2() {
        $configuration = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', '/some/thing/sapi/cli/php', 'test.phpt'));
        $setting = new rtPhpCgiExecutableSetting($configuration);

        $this->assertEquals('/some/thing/sapi/cgi/php-cgi', $setting->get());
    }
 	public function testSetFromCli3() {
        $configuration = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', '/some/thing/sapi/cli/phpblah', 'test.phpt'));
        $setting = new rtPhpCgiExecutableSetting($configuration);

        $this->assertEquals(null, $setting->get());
    }
    
}
?>
