<?php

require_once dirname(__FILE__) . '../../../../src/rtAutoload.php';

class rtLogFormatSettingTest extends PHPUnit_Framework_TestCase
{
    public function testByEnvironmentSetting() {
        $configuration = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', 'a-php-exe', 'test.phpt'));
        $configuration->setEnvironmentVariable('TEST_PHP_LOG_FORMAT', 'some-log-format');

        $setting = new rtLogFormatSetting($configuration);

        $this->assertEquals('SOME-LOG-FORMAT', $setting->get());
    }

    public function testSetLEOD() {
        $configuration = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', 'a-php-exe', 'test.phpt'));
        $setting = new rtLogFormatSetting($configuration);

        $this->assertEquals('LEOD', $setting->get());
    }
}
?>
