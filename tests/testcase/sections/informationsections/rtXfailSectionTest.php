<?php

require_once dirname(__FILE__) . '../../../../../src/rtAutoload.php';

class rtXfailSectionTest extends PHPUnit_Framework_TestCase
{
    public function testCreateInstance()
    {
        $xfailSection = rtXfailSection::getInstance('XFAIL', array('Bug number 12345'), 'testname');  
        $xfail = $xfailSection->getReason();

        $this->assertEquals('Bug number 12345', $xfail);
    }
}
?>
