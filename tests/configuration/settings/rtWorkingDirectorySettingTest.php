<?php

require_once dirname(__FILE__) . '../../../../src/rtAutoload.php';

class rtWorkingDirectorySettingTest extends PHPUnit_Framework_TestCase
{
    public function testSet()
    {
        $configuration = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', 'a-php-exe', 'test.phpt'));
        $setting = new rtWorkingDirectorySetting($configuration);

        $this->assertEquals(getcwd(), $setting->get());
    }

    public function testSetFromEnv()
    {
        $configuration = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', 'a-php-exe', 'test.phpt'));
        $configuration->setEnvironmentVariable('TEST_PHP_SRCDIR', 'the-source-dir');
        $setting = new rtWorkingDirectorySetting($configuration);

        $this->assertEquals('the-source-dir', $setting->get());
    }
}
?>
