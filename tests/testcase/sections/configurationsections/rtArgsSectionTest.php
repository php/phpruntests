<?php

require_once dirname(__FILE__) . '../../../../../src/rtAutoload.php';

class rtArgsSectionTest extends PHPUnit_Framework_TestCase
{
    public function testCreateInstance() 
    {
        $argsSection = rtArgsSection::getInstance('ARGS', array('-vvv -a value -1111 -2 -v'), 'testname');  
        $arglist = $argsSection->getTestCommandLineArguments();

        $this->assertEquals('-- -vvv -a value -1111 -2 -v', $arglist);
    }
}
?>
