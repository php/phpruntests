<?php
require_once dirname(__FILE__) . '../../../../src/rtAutoload.php';

class rtIsNotRedirectedTest extends PHPUnit_Framework_TestCase
{
    public function testIs()
    {
        $precondition = new rtIsNotRedirected();
        $test = array('TEST', 'FILE', 'EXPECT');

        $this->assertTrue($precondition->isMet(array(), $test));
    }

    public function testIsNot()
    {
        $precondition = new rtIsNotRedirected();
        $test = array('UEXPECT', 'REDIRECTTEST');

        $this->assertEquals("This test has a REDIRECTTEST section", trim($precondition->getMessage()));
        $this->assertFalse($precondition->isMet(array(), $test));
    }
}
?>