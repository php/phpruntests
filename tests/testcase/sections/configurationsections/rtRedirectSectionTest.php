<?php
require_once dirname(__FILE__) . '../../../../../src/rtAutoload.php';

class rtRedirectSectionTest extends PHPUnit_Framework_TestCase
{
    public function testCreateInstance() 
    {
        $redirectSection = rtRedirectSection::getInstance('REDIRECTTEST', 'contents', 'testname');  

        $this->assertEquals('contents', $redirectSection->getContents());
    }
}
?>
