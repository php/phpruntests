<?php
require_once dirname(__FILE__) . '../../../../../src/rtAutoload.php';

class rtPostRawSectionTest extends PHPUnit_Framework_TestCase
{
    public function testCreateInstance() 
    {
        $post_array = array( 'fred',
                             'Content-Type:the first',
                             'joe',
                             'Content-Type:the second',
                             'mary',
                            );
        $postSection = rtPostRawSection::getInstance('POST_RAW', $post_array, 'testname');  
        
        $envVars = $postSection->getPostVariables();
        $this->assertEquals('POST', $envVars['REQUEST_METHOD']);
        $this->assertEquals('the first',$envVars['CONTENT_TYPE']);
        
        $fileName = $postSection->getPostFileName();
        $string = file_get_contents($fileName);        
        $this->assertEquals("fred\njoe\nContent-Type:the second\nmary", $string);
    }
}
?>
