<?php

require_once dirname(__FILE__) . '../../../../../src/rtAutoload.php';

class rtTestHeaderSectionTest extends PHPUnit_Framework_TestCase
{
    public function testCreateInstance()
    {
        $headerSection = rtTestHeaderSection::getInstance('TEST', array('a test to test something'), 'testname');  
        $header = $headerSection->getHeader();

        $this->assertEquals('a test to test something', $header);
    }
}
?>
