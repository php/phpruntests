<?php
require_once dirname(__FILE__) . '../../../../../src/rtAutoload.php';

class rtDeflatePostSectionTest extends PHPUnit_Framework_TestCase
{
    public function testCreateInstance() 
    {
        $postSection = rtDeflatePostSection::getInstance('DEFLATE_POST', array('hello=World&goodbye=MrChips'), 'testname');  
        
        $envVars = $postSection->getPostVariables();
        $this->assertEquals('POST', $envVars['REQUEST_METHOD']);
        $this->assertEquals('application/x-www-form-urlencoded',$envVars['CONTENT_TYPE']);
        $this->assertEquals(strlen(gzcompress('hello=World&goodbye=MrChips')), $envVars['CONTENT_LENGTH']);
        
        $fileName = $postSection->getPostFileName();
        $string = file_get_contents($fileName);
        
        $expectedString = gzcompress('hello=World&goodbye=MrChips');
        
        $this->assertEquals($expectedString, $string);
    }
}
?>
