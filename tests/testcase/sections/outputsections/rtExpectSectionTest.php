<?php

require_once dirname(__FILE__) . '../../../../../src/rtAutoload.php';

class rtExpectSectionTest extends PHPUnit_Framework_TestCase
{
    public function testCreatePattern()  {
        $expectSection = rtExpectSection::getInstance('EXPECT', array('Hello World'), 'testname');     
        $pattern = $expectSection->getPattern();

        $this->assertEquals('Hello World', $pattern);
    }


    public function testCreateTwolinePattern()
    {
        $expectSection = rtExpectSection::getInstance('EXPECT', array('Hello World', 'Hello again'), 'testname'); 
        $pattern = $expectSection->getPattern();

        $this->assertEquals("Hello World\nHello again", $pattern);
    }

    public function testCreateTwolinePatternWithr()
    {
        $expectSection = rtExpectSection::getInstance('EXPECT', array("Hello World\r", 'Hello again'), 'testname');  
        $pattern = $expectSection->getPattern();

        $this->assertEquals("Hello World\nHello again", $pattern);
    }

    public function testCompare()
    {
        $expectSection = rtExpectSection::getInstance('EXPECT', array('Hello World'), 'testname' );
        $result = $expectSection->compare('Hello World');

        $this->assertTrue($result);
    }
}
?>
