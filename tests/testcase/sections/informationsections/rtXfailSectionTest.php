<?php
require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__) . '../../../../../src/rtAutoload.php';


class rtXfailSectionTest extends PHPUnit_Framework_TestCase
{
  
public function testCreateInstance()  {
  
    $xfailSection = new rtXfailSection('XFAIL',array('Bug number 12345'));  
    $xfail = $xfailSection->getReason();
    $this->assertEquals('Bug number 12345', $xfail);
    
  }
}
?>