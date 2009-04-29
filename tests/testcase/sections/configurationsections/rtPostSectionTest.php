<?php
require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__) . '../../../../../src/rtAutoload.php';

class rtPostSectionTest extends PHPUnit_Framework_TestCase
{
    public function testCreateInstance() 
    {
        $postSection = new rtPostSection('POST', array('hello=World&goodbye=MrChips'));  
        
        $envVars = $postSection->getPostVariables();
        $this->assertEquals('POST', $envVars['REQUEST_METHOD']);
        
        $fileName = $postSection->getPostFileName();
        $string = file_get_contents($fileName);
        
        $this->assertEquals('hello=World&goodbye=MrChips', $string);
    }
}
?>