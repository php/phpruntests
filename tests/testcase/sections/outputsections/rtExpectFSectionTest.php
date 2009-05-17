<?php

require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__) . '../../../../../src/rtAutoload.php';

class rtExpectFSectionTest extends PHPUnit_Framework_TestCase
{
    public function testCreatePattern()
    {
        $expectFSection = rtExpectFSection::getInstance('EXPECTF', array('Hello World'));
        $pattern = $expectFSection->getPattern();

        $this->assertEquals('Hello World', $pattern);
    }

    public function testPercentE()
    {
        $expectFSection = rtExpectFSection::getInstance('EXPECTF', array('Hello %e'));
        $pattern = $expectFSection->getPattern();

        $this->assertEquals("Hello \\/", $pattern);
    }

    public function testPercentS()
    {
        $expectFSection = rtExpectFSection::getInstance('EXPECTF', array('Hello %s'));
        $pattern = $expectFSection->getPattern();

        $this->assertEquals('Hello [^\r\n]+', $pattern);
    }

    public function testPercentA() 
    {
        $expectFSection = rtExpectFSection::getInstance('EXPECTF', array('Hello %a'));
        $pattern = $expectFSection->getPattern();

        $this->assertEquals('Hello .+', $pattern);
    }

    public function testPercentW()
    {
        $expectFSection = rtExpectFSection::getInstance('EXPECTF', array('Hello %w'));
        $pattern = $expectFSection->getPattern();

        $this->assertEquals('Hello \s*', $pattern);
    }

    public function testPercentI()
    {
        $expectFSection = rtExpectFSection::getInstance('EXPECTF', array('Hello %i'));
        $pattern = $expectFSection->getPattern();

        $this->assertEquals('Hello [+-]?\d+', $pattern);
    }

    public function testPercentD()
    {
        $expectFSection = rtExpectFSection::getInstance('EXPECTF', array('Hello %d'));
        $pattern = $expectFSection->getPattern();

        $this->assertEquals('Hello \d+', $pattern);
    }

    public function testPercentX()
    {
        $expectFSection = rtExpectFSection::getInstance('EXPECTF', array('Hello %x'));
        $pattern = $expectFSection->getPattern();

        $this->assertEquals('Hello [0-9a-fA-F]+', $pattern);
    }

    public function testPercentF() 
    {
        $expectFSection = rtExpectFSection::getInstance('EXPECTF', array('Hello %f'));
        $pattern = $expectFSection->getPattern();

        $this->assertEquals('Hello [+-]?\.?\d+\.?\d*(?:[Ee][+-]?\d+)?', $pattern);
    }
    
    public function testPercentR() 
    {
        $expectFSection = rtExpectFSection::getInstance('EXPECTF', array('%unicode|string%(18) "%r\0%rMyClass%r\0%rpri_value%r\0%r"'));
        $pattern = $expectFSection->getPattern();

        $this->assertEquals('string\(18\) "(\0)MyClass(\0)pri_value(\0)"', $pattern);
    }

    public function testPercentC() 
    {
        $expectFSection = rtExpectFSection::getInstance('EXPECTF', array('Hello %c'));
        $pattern = $expectFSection->getPattern();

        $this->assertEquals('Hello .', $pattern);
    }

    public function testCompare()
    {
        $expectFSection = rtExpectFSection::getInstance('EXPECTF', array('Hello %s') );
        $result = $expectFSection->compare('Hello World');

        $this->assertTrue($result);
    }
}
?>
