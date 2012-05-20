<?php

require_once dirname(__FILE__) . '../../../../src/rtAutoload.php';

class rtIsSectionImplementedTest extends PHPUnit_Framework_TestCase
{
    public function testIs()
    {
        $precondition = new rtIsSectionImplemented();
        $test = array('TEST', 'FILE', 'EXPECT');

        $this->assertTrue($precondition->isMet(array(), $test));
    }

    public function testIsNot()
    {
        $precondition = new rtIsSectionImplemented();
        $test = array('FILEEOF', 'FILE');

        $this->assertEquals("The test contains a section which is not implemented yet.", trim($precondition->getMessage()));
        $this->assertFalse($precondition->isMet(array(), $test));
    }
}
?>
