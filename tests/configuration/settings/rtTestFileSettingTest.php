<?php
require_once dirname(__FILE__) . '../../../../src/rtAutoload.php';


class rtTestFileSettingTest extends PHPUnit_Framework_TestCase
{
    private $f1;
    private $f2;
    private $testnames = array(
        '/a.phpt',
        '/b.phpt',
        '/c.phpt',
    );

    public function setUp()
    {
        $this->f1 = sys_get_temp_dir() . "/file1";
        $fh = fopen($this->f1, 'w');

        foreach ($this->testnames as $line) {
            fwrite($fh, sys_get_temp_dir() . $line . "\n");
        }

        fclose($fh);
    }

    public function tearDown() 
    {
        unlink($this->f1);
    }

    public function testSetFileList()
    {
        $configuration = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', 'a-php-exe', 'the-first-file.phpt', 'the-second-file.phpt' ));
        $testfilesetting = new rtTestFileSetting($configuration);
         
        $filelist = $testfilesetting->get();

        $this->assertEquals($filelist[0], 'the-first-file.phpt');
        $this->assertEquals($filelist[1], 'the-second-file.phpt');
    }

    public function testSetLOption()
    {
        $configuration = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', 'a-php-exe', '-l', $this->f1 ));
        $testfilesetting = new rtTestFileSetting($configuration);
         
        $filelist = $testfilesetting->get();

        $this->assertEquals($filelist[0], sys_get_temp_dir() . '/a.phpt');
        $this->assertEquals($filelist[1], sys_get_temp_dir() . '/b.phpt');
    }

    public function testSetROption()
    {
        $configuration = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', 'a-php-exe', '-r', $this->f1 ));
        $testfilesetting = new rtTestFileSetting($configuration);
         
        $filelist = $testfilesetting->get();

        $this->assertEquals($filelist[0], sys_get_temp_dir() . '/a.phpt');
        $this->assertEquals($filelist[1], sys_get_temp_dir() . '/b.phpt');
    }
}
?>
