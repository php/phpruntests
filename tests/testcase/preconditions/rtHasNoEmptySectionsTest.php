<?php

require_once dirname(__FILE__) . '../../../../src/rtAutoload.php';

class rthasNoEmptySectionsTest extends PHPUnit_Framework_TestCase
{
    public function testValid()
    {
        $precondition = new rtHasNoEmptySections();
        $test = array('--TEST--', 'something', '--FILE--', 'something', '--EXPECT--');

        $this->assertTrue($precondition->isMet($test, array()));
    }

    public function testInvalid()
    {
        $precondition = new rthasNoEmptySections();
        $test = array('--TEST--', 'something', '--FILE--', '--EXPECT--');

        $this->assertEquals("The test has an empty section.", trim($precondition->getMessage()));
        $this->assertFalse($precondition->isMet($test, array()));
    }
}

?>
