<?php
require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__) . '../../../../../src/rtAutoload.php';

class rtGetSectionTest extends PHPUnit_Framework_TestCase
{
    public function testCreateInstance() 
    {
        $getSection = new rtGetSection('GET', array('hello=World&goodbye=MrChips'));  
        $envlist = $getSection->getGetVariables();

        $this->assertEquals('hello=World&goodbye=MrChips', $envlist['QUERY_STRING']);
    }
}
?>