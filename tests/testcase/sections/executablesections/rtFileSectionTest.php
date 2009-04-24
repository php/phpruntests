<?php

require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__) . '../../../../../src/rtAutoload.php';

class rtSkipIfSectionTest extends PHPUnit_Framework_TestCase
{
    public function testCreateInstance()
    {
        $skipifSection = new rtSkipIfSection('SKIPIF', array('<?php', 'echo "hello world";', '?>')); 
        $code = $skipifSection->getContents();

        $this->assertEquals('<?php', $code[0]);
    }
}
?>
