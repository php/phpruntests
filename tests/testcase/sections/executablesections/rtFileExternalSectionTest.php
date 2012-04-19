<?php

require_once dirname(__FILE__) . '../../../../../src/rtAutoload.php';

class rtFileExternalSectionTest extends PHPUnit_Framework_TestCase
{
    public function testCreateInstance()
    {
        $fileSection = rtFileExternalSection::getInstance('FILE_EXTERNAL', array('<?php', 'echo "hello world";', '?>'), 'testname');
        $code = $fileSection->getContents();

        $this->assertEquals('<?php', $code[0]);
    }
}

?>
