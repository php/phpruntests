<?php

require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__) . '../../../../src/rtAutoload.php';

class rtIsPcreLoadedTest extends PHPUnit_Framework_TestCase
{
    public function testLoaded()
    {
        $pre = new rtIsPcreLoaded();
        $this->assertTrue($pre->check());
    }
  
    public function testgetMessage()
    {
        $pre = new rtIsPcreLoaded();
        $this->assertEquals($pre->getMessage('pcreNotLoaded'), rtText::get('pcreNotLoaded'));
    }
    
    //Not sure how to check if it's not loaded?
}
?>
