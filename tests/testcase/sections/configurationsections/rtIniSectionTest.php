<?php

require_once dirname(__FILE__) . '../../../../../src/rtAutoload.php';

class rtIniSectionTest extends PHPUnit_Framework_TestCase
{
    public function testCreateInstance()
    {
        $iniSection = rtIniSection::getInstance('INI', array('error_reporting=E_ALL | E_STRICT | E_DEPRECATED', 'assert.active = 1'), 'testname');
        $iniSection->substitutePWD('a-file-name');
        $inilist = $iniSection->getCommandLineArguments();

        $this->assertEquals('error_reporting=E_ALL | E_STRICT | E_DEPRECATED', $inilist[0]);
        $this->assertEquals('assert.active = 1', $inilist[1]);
    }
    public function testSubtitutePWD()
    {
        $iniSection = rtIniSection::getInstance('INI', array('include_path={PWD}'), 'testname');
        $afile = __FILE__;
        $iniSection->substitutePWD($afile);
        $inilist = $iniSection->getCommandLineArguments();

        $this->assertEquals('include_path=' . dirname($afile), $inilist[0]);
    }
}
?>
