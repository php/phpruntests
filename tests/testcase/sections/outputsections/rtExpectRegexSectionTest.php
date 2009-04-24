<?php

require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__) . '../../../../../src/rtAutoload.php';

class rtExpectRegexSectionTest extends PHPUnit_Framework_TestCase
{
    public function testCreateObject()
    {
        $expectRegexSection = new rtExpectRegexSection('EXPECTREGEX', array('Hello \w{5}'));
        $pattern = $expectRegexSection->getPattern();

        $this->assertEquals("Hello \w{5}", $pattern);
    }

    public function testCompare()
    {
        $expectRegexSection = new rtExpectRegexSection('EXPECTREGEX', array('Hello \w{5}'));
        $result = $expectRegexSection->compare('Hello World');

        $this->assertTrue($result);
    }
}
?>
