<?php

require_once dirname(__FILE__) . '../../../../src/rtAutoload.php';

class rtHasMandatorySectionsTest extends PHPUnit_Framework_TestCase
{
    public function testHas()
    {
        $precondition = new rtHasMandatorySections();
        $test = array('TEST','FILE', 'EXPECT');
        $this->assertTrue($precondition->isMet(array(), $test));
    }

    public function testHasNot()
    {
        $precondition = new rtHasMandatorySections();
        $test = array('TEST', 'FILE');
        $this->assertEquals("The test case is missing one or more mandatory sections.", trim($precondition->getMessage()));
        $this->assertFalse($precondition->isMet(array(), $test));
    }
}
?>
