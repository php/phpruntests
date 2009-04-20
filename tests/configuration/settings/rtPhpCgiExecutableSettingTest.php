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
        $configuration = rtRuntestsConfiguration::getInstance(array('run-tests.php', 'test.phpt'));
        $configuration->setEnvironmentVariable('TEST_PHP_CGI_EXECUTABLE', 'auto');

        $cgisetting = new rtPhpCgiExecutableSetting($configuration);

        $this->assertEquals('WORKING_DIR/sapi/cgi/php', $cgisetting->get());
    }

    public function testSetPhpCgiExecutableNotSet() {
        $configuration = rtRuntestsConfiguration::getInstance(array('run-tests.php', 'test.phpt'));
        
        $cgisetting = new rtPhpCgiExecutableSetting($configuration);
        
        $this->assertEquals(null, $cgisetting->get());      
    }
}
?>
