<?php
require_once dirname(__FILE__) . '../../../../../src/rtAutoload.php';

class rtGetSectionTest extends PHPUnit_Framework_TestCase
{
    public function testCreateInstance() 
    {
        $getSection = rtGetSection::getInstance('GET', array('hello=World&goodbye=MrChips'), 'testname');  
        $envlist = $getSection->getGetVariables();

        $this->assertEquals('hello=World&goodbye=MrChips', $envlist['QUERY_STRING']);
    }
}
?>
