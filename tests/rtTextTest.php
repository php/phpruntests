<?php

require_once dirname(__FILE__) . '/../src/rtAutoload.php';

class rtHelpTextTest extends PHPUnit_Framework_TestCase
{
    public function testOutput()
    {
        $words = rtText::get('help');
        $this->assertEquals(substr($words, 0, 9), 'Synopsis:');
    }
}
?>
