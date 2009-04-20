<?php

require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__) . '../../../../src/rtAutoload.php';

class rtTestDirectorySettingsTest extends PHPUnit_Framework_TestCase
{
    private $d1;
    private $d2;

    public function setUp()
    {
        $this->d1 = sys_get_temp_dir();
        $this->d2 = sys_get_temp_dir();
    }

    public function testSet()
    {
        $configuration = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', 'a-php-exe', $this->d1, $this->d2));
        $testdirsetting = new rtTestDirectorySetting($configuration);
         
        $dirlist = $testdirsetting->get();

        $this->assertEquals($dirlist[0], $this->d1);
        $this->assertEquals($dirlist[1], $this->d2);
    }
}
?>
