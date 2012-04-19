<?php

require_once dirname(__FILE__) . '../../../../../src/rtAutoload.php';

class rtExpectFSectionTest extends PHPUnit_Framework_TestCase
{
    public function testCreatePattern()
    {
        $expectFSection = rtExpectFSection::getInstance('EXPECTF', array('Hello World'), 'testname');
        $pattern = $expectFSection->getPattern();

        $this->assertEquals('Hello World', $pattern);
    }

    public function testPercentE()
    {
        $expectFSection = rtExpectFSection::getInstance('EXPECTF', array('Hello %e'), 'testname');
        $pattern = $expectFSection->getPattern();

        $this->assertEquals("Hello \\/", $pattern);
    }

    public function testPercentS()
    {
        $expectFSection = rtExpectFSection::getInstance('EXPECTF', array('Hello %s'), 'testname');
        $pattern = $expectFSection->getPattern();

        $this->assertEquals('Hello [^\r\n]+', $pattern);
    }

    public function testPercentA() 
    {
        $expectFSection = rtExpectFSection::getInstance('EXPECTF', array('Hello %a'), 'testname');
        $pattern = $expectFSection->getPattern();

        $this->assertEquals('Hello .+', $pattern);
    }

    public function testPercentW()
    {
        $expectFSection = rtExpectFSection::getInstance('EXPECTF', array('Hello %w'), 'testname');
        $pattern = $expectFSection->getPattern();

        $this->assertEquals('Hello \s*', $pattern);
    }

    public function testPercentI()
    {
        $expectFSection = rtExpectFSection::getInstance('EXPECTF', array('Hello %i'), 'testname');
        $pattern = $expectFSection->getPattern();

        $this->assertEquals('Hello [+-]?\d+', $pattern);
    }

    public function testPercentD()
    {
        $expectFSection = rtExpectFSection::getInstance('EXPECTF', array('Hello %d'), 'testname');
        $pattern = $expectFSection->getPattern();

        $this->assertEquals('Hello \d+', $pattern);
    }

    public function testPercentX()
    {
        $expectFSection = rtExpectFSection::getInstance('EXPECTF', array('Hello %x'), 'testname');
        $pattern = $expectFSection->getPattern();

        $this->assertEquals('Hello [0-9a-fA-F]+', $pattern);
    }

    public function testPercentF() 
    {
        $expectFSection = rtExpectFSection::getInstance('EXPECTF', array('Hello %f'), 'testname');
        $pattern = $expectFSection->getPattern();

        $this->assertEquals('Hello [+-]?\.?\d+\.?\d*(?:[Ee][+-]?\d+)?', $pattern);
    }
    
    public function testPercentR() 
    {
        $expectFSection = rtExpectFSection::getInstance('EXPECTF', array('%unicode|string%(18) "%r\0%rMyClass%r\0%rpri_value%r\0%r"'), 'testname');
        $pattern = $expectFSection->getPattern();

        $this->assertEquals('string\(18\) "(\0)MyClass(\0)pri_value(\0)"', $pattern);
    }

    public function testPercentC() 
    {
        $expectFSection = rtExpectFSection::getInstance('EXPECTF', array('Hello %c'), 'testname');
        $pattern = $expectFSection->getPattern();

        $this->assertEquals('Hello .', $pattern);
    }

    public function testCompare()
    {
        $expectFSection = rtExpectFSection::getInstance('EXPECTF', array('Hello %s'), 'testname' );
        $result = $expectFSection->compare('Hello World');

        $this->assertTrue($result);
    }
}
?>
