<?php

require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__) . '../../../../src/rtAutoload.php';

class rtPhpExecutableSettingTest extends PHPUnit_Framework_TestCase
{
    public function testSetPhpExecutable()
    {
        $configuration = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', 'a-php-executable'));

        $clisetting = new rtPhpExecutableSetting($configuration);

        $this->assertEquals('a-php-executable',  $clisetting->get());
    }

    public function testSetPhpExecutableEV()
    {
        $configuration = rtRuntestsConfiguration::getInstance(array('run-tests.php'));
        $configuration->setEnvironmentVariable('TEST_PHP_EXECUTABLE', 'a-php-executable');

        $clisetting = new rtPhpExecutableSetting($configuration);

        $this->assertEquals('a-php-executable',  $clisetting->get());
    }

    public function testSetPhpExecutableEVAuto()
    {
        $configuration = rtRuntestsConfiguration::getInstance(array('run-tests.php', 'test.phpt'));
        $configuration->setEnvironmentVariable('TEST_PHP_EXECUTABLE', 'auto');

        $clisetting = new rtPhpExecutableSetting($configuration);

        $this->assertEquals('WORKING_DIR/sapi/cli/php', $clisetting->get());      
    }
}
?>
