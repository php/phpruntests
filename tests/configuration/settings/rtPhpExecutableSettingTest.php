<?php

require_once dirname(__FILE__) . '../../../../src/rtAutoload.php';

class rtPhpExecutableSettingTest extends PHPUnit_Framework_TestCase
{
    public function testSetPhpExecutableFromCommandLineOption()
    {
        $configuration = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', 'a-php-executable'));
        $setting = new rtPhpExecutableSetting($configuration);

        $this->assertEquals('a-php-executable', $setting->get());
    }

    public function testSetPhpExecutableFromEnvironmentVariable()
    {
        $configuration = rtRuntestsConfiguration::getInstance(array('run-tests.php'));
        $configuration->setEnvironmentVariable('TEST_PHP_EXECUTABLE', 'a-php-executable');
        $setting = new rtPhpExecutableSetting($configuration);

        $this->assertEquals('a-php-executable', $setting->get());
    }

    public function testSetPhpExecutableAutomaticallyFromEnvironmentVariable()
    {
        $configuration = rtRuntestsConfiguration::getInstance(array('run-tests.php', 'test.phpt'));
        $configuration->setEnvironmentVariable('TEST_PHP_EXECUTABLE', 'auto');
        $configuration->setEnvironmentVariable('TEST_PHP_SRCDIR', '/some/directory');
        $setting = new rtPhpExecutableSetting($configuration);

        $this->assertEquals('/some/directory/sapi/cli/php', $setting->get('PhpExecutable'));
    }

    public function testSetPhpExecutableAutomaticallyFromEnvironmentVariableAndWorkingDirectory()
    {
        $configuration = rtRuntestsConfiguration::getInstance(array('run-tests.php', 'test.phpt'));
        $configuration->setEnvironmentVariable('TEST_PHP_EXECUTABLE', 'auto');
        $setting = new rtPhpExecutableSetting($configuration);

        $this->assertEquals(realpath(getcwd()).'/sapi/cli/php', $setting->get('PhpExecutable'));
    }
}
?>
