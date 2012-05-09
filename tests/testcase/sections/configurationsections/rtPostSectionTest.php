<?php
require_once dirname(__FILE__) . '../../../../../src/rtAutoload.php';

class rtPostSectionTest extends PHPUnit_Framework_TestCase
{
    public function testCreateInstance() 
    {
        $postSection = rtPostSection::getInstance('POST', array('hello=World&goodbye=MrChips'), 'testname');  
        
        $envVars = $postSection->getPostVariables();
        $this->assertEquals('POST', $envVars['REQUEST_METHOD']);
        
        $fileName = $postSection->getPostFileName();
        $string = file_get_contents($fileName);
        
        //clean up
        unlink($fileName);
        
        $this->assertEquals('hello=World&goodbye=MrChips', $string);
    }
}
?>
