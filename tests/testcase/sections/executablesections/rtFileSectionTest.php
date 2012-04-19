<?php

require_once dirname(__FILE__) . '../../../../../src/rtAutoload.php';

class rtFileSectionTest extends PHPUnit_Framework_TestCase
{
    public function testCreateInstance()
    {
        $fileSection = rtFileSection::getInstance('FILE', array('<?php', 'echo "hello world";', '?>'), 'testname');
        $code = $fileSection->getContents();

        $this->assertEquals('<?php', $code[0]);
    }
}
?>
