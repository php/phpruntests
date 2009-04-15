<?php
require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__) . '../../../../src/rtAutoload.php';


class rtIssafeModeDisabledTest extends PHPUnit_Framework_TestCase
{
  public function testDisabled() {     
    $pre = new rtIsSafeModeDisabled();
    $this->assertTrue($pre->check());
  }
  public function testgetMessage() {     
    $pre = new rtIsSafeModeDisabled();
    $this->assertEquals($pre->getMessage('safeModeNotDisabled'), rtText::get('safeModeNotDisabled'));
  }
  
  //Not sure how to check if it is missing?
}
?>