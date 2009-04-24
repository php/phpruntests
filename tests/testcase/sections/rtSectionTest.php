<?php

require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__) . '../../../../src/rtAutoload.php';

class rtSectionTest extends PHPUnit_Framework_TestCase
{
    public function testGetInstance()
    {
        $expectFSection = rtSection::getInstance('EXPECTF', array('Hello World'));
        $this->assertEquals('rtExpectFSection', get_class($expectFSection));
    }

    /**
    * @expectedException RuntimeException
    */
    public function testGetInstanceThrowsExceptionOnUnknownSection()
    {
        rtSection::getInstance('NONSENSE', array());
    }
}
?>
