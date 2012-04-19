<?php

require_once dirname(__FILE__) . '../../../../../src/rtAutoload.php';

class rtSkipifSectionTest extends PHPUnit_Framework_TestCase
{
    public function testCreateInstance()
    {
        $skipifSection = rtSkipIfSection::getInstance('SKIPIF', array('<?php', 'echo "hello world";', '?>'), 'testname'); 
        $code = $skipifSection->getContents();

        $this->assertEquals('<?php', $code[0]);
    }
}
?>
