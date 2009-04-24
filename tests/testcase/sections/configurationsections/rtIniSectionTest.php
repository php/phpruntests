<?php

require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__) . '../../../../../src/rtAutoload.php';

class rtIniSectionTest extends PHPUnit_Framework_TestCase
{
    public function testCreateInstance()
    {
        $iniSection = new rtIniSection('INI', array('error_reporting=E_ALL | E_STRICT | E_DEPRECATED', 'assert.active = 1'));  
        $inilist = $iniSection->getCommandLineArguments();

        $this->assertEquals('error_reporting=E_ALL | E_STRICT | E_DEPRECATED', $inilist[0]);
        $this->assertEquals('assert.active = 1', $inilist[1]);
    }
}
?>
