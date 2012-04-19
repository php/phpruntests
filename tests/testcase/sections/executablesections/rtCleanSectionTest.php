<?php

require_once dirname(__FILE__) . '../../../../../src/rtAutoload.php';

class rtCleanSectionTest extends PHPUnit_Framework_TestCase
{
    public function testCreateInstance()
    {
        $cleanSection = rtCleanSection::getInstance('CLEAN', array('<?php', 'echo "hello world";', '?>'), 'testname'); 
        $code = $cleanSection->getContents();
 
        $this->assertEquals('<?php', $code[0]);
    }
}
?>
