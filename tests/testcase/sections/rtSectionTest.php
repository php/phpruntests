<?php

require_once dirname(__FILE__) . '../../../../src/rtAutoload.php';

class rtSectionTest extends PHPUnit_Framework_TestCase
{
    public function testGetInstance()
    {
        $expectFSection = rtSection::getInstance('EXPECTF', array('Hello World'), 'testname');
        $this->assertEquals('rtExpectFSection', get_class($expectFSection));
    }

    /**
    * @expectedException rtException
    */
    public function testGetInstanceThrowsExceptionOnUnknownSection()
    {
        rtSection::getInstance('NONSENSE', array(), 'testname');
    }
}
?>
