<?php

require_once dirname(__FILE__) . '../../../../../src/rtAutoload.php';

class rtExpectRegexSectionTest extends PHPUnit_Framework_TestCase
{
    public function testCreateObject()
    {
        $expectRegexSection = rtExpectRegexSection::getInstance('EXPECTREGEX', array('Hello \w{5}'), 'testname');
        $pattern = $expectRegexSection->getPattern();

        $this->assertEquals("Hello \w{5}", $pattern);
    }

    public function testCompare()
    {
        $expectRegexSection = rtExpectRegexSection::getInstance('EXPECTREGEX', array('Hello \w{5}'), 'testname');
        $result = $expectRegexSection->compare('Hello World');

        $this->assertTrue($result);
    }
}
?>
