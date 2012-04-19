<?php

require_once dirname(__FILE__) . '../../../../src/rtAutoload.php';

class rtHasNoDuplicateSectionTest extends PHPUnit_Framework_TestCase
{
    public function testNoDup()
    {
        $precondition = new rtHasNoDuplicateSections();
        $test = array('TEST', 'FILE');

        $this->assertTrue($precondition->isMet(array(), $test));
    }

    public function testDup()
    {
        $precondition = new rtHasNoDuplicateSections();
        $test = array('TEST', 'TEST');

        $this->assertEquals("The test has duplicate sections.", trim($precondition->getMessage()));
        $this->assertFalse($precondition->isMet(array(), $test));
    }
}
?>
