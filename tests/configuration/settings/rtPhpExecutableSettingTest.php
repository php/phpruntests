<?php

require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__) . '../../../../src/rtAutoload.php';

class rtPhpExecutableSettingTest extends PHPUnit_Framework_TestCase
{
    public function testSetPhpExecutable()
    {
        $configuration = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', 'a-php-executable'));

        $clisetting = new rtPhpExecutableSetting($configuration);

        $this->assertEquals('a-php-executable', $clisetting->get());
    }

    public function testSetPhpExecutableEV()
    {
        $configuration = rtRuntestsConfiguration::getInstance(array('run-tests.php'));
        $configuration->setEnvironmentVariable('TEST_PHP_EXECUTABLE', 'a-php-executable');

        $clisetting = new rtPhpExecutableSetting($configuration);

        $this->assertEquals('a-php-executable', $clisetting->get());
    }

    public function testSetPhpExecutableEvAuto()
    {
        $configuration = rtRuntestsConfiguration::getInstance(array('run-tests.php', 'test.phpt'));
        $configuration->setEnvironmentVariable('TEST_PHP_EXECUTABLE', 'auto');
        $configuration->setEnvironmentVariable('TEST_PHP_SRCDIR', '/some/directory');
        $configuration->configure();
        $this->assertEquals('/some/directory/sapi/cli/php', $configuration->getSetting('PhpExecutable'));
    }

    public function testSetPhpExecutableCwdAuto()
    {
        $configuration = rtRuntestsConfiguration::getInstance(array('run-tests.php', 'test.phpt'));
        $configuration->setEnvironmentVariable('TEST_PHP_EXECUTABLE', 'auto');
        $configuration->configure();

        $this->assertEquals(realpath(getcwd()).'/sapi/cli/php', $configuration->getSetting('PhpExecutable'));
    }
}
?>
