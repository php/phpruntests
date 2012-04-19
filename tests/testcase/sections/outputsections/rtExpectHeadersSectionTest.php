<?php

require_once dirname(__FILE__) . '../../../../../src/rtAutoload.php';

class rtExpectHeadersSectionTest extends PHPUnit_Framework_TestCase
{
    public function testCreatePattern()  {
        $expectSection = rtExpectHeadersSection::getInstance('EXPECTHEADERS', array('abc:def', 'ghi:jkl'), 'testname');
        $pattern = $expectSection->getPattern();

        $this->assertEquals('def', $pattern['abc']);
        $this->assertEquals('jkl', $pattern['ghi']);
    }

    public function testCreatePattern2()  {
        $expectSection = rtExpectHeadersSection::getInstance('EXPECTHEADERS', array("abc:def\r\n", "ghi:jkl:fred"), 'testname');
        $pattern = $expectSection->getPattern();

        $this->assertEquals('def', $pattern['abc']);
        $this->assertEquals('jkl:fred', $pattern['ghi']);
    }
    
    public function testCompare()  {
        $expectSection = rtExpectHeadersSection::getInstance('EXPECTHEADERS', array("abc:def\r\n", "ghi:jkl:fred"), 'testname');
        $test = $expectSection->compare("ghi:jkl:fred\nabc: def");

        $this->assertTrue($test);
    }
    
    public function testCompare2()  {
        $expectSection = rtExpectHeadersSection::getInstance('EXPECTHEADERS', array("abc:def\r\n", "ghi:jkl:fred"), 'testname');
        $test = $expectSection->compare("ghi:jkl\r\nabcd: def\r\n");

        $this->assertFalse($test);
    }
    

}
?>
