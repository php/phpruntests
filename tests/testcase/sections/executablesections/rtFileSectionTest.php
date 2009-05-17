<?php

require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__) . '../../../../../src/rtAutoload.php';

class rtFileSectionTest extends PHPUnit_Framework_TestCase
{
    public function testCreateInstance()
    {
        $fileSection = rtFileSection::getInstance('FILE', array('<?php', 'echo "hello world";', '?>'));
        $code = $fileSection->getContents();

        $this->assertEquals('<?php', $code[0]);
    }
    public function testDone()
    {
        $fileSection = rtFileSection::getInstance('FILE', array('<?php', 'echo "hello world";', '?>', '===DONE===', 'ignore-me'));
        $code = $fileSection->getContents();
        $last = count($code) - 1;

        $this->assertEquals('===DONE===', $code[$last]);
    }

    public function testDone2()
    {
        $fileSection = rtFileSection::getInstance('FILE', array('<?php', 'echo "hello world";', '?>', '===DoNe===', 'ignore-me'));
        $code = $fileSection->getContents();
        $last = count($code) - 1;

        $this->assertEquals('===DoNe===', $code[$last]);
    }
}
?>
