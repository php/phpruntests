<?php

require_once dirname(__FILE__) . '../../../../src/rtAutoload.php';

class rtIsValidSectionNameTest extends PHPUnit_Framework_TestCase
{
    public function testValid()
    {
        $precondition = new rtIsValidSectionName();
        $test = array('TEST',  'FILE');

        $this->assertTrue($precondition->isMet(array(), $test));
    }

    public function testInvalid()
    {
        $precondition = new rtIsValidSectionName();
        $test = array('TEXT', 'FILE');

        $this->assertEquals("Test case contains an invalid section.", trim($precondition->getMessage()));
        $this->assertFalse($precondition->isMet(array(), $test));
    }
}

?>
