<?php


require_once 'PHPUnit/Framework.php';
  require_once dirname(__FILE__) . '../../../src/rtAutoload.php';


  class rtTestDifferenceTest extends PHPUnit_Framework_TestCase {
    
    public function testTestDifference() {
      
    $expectSection = new rtExpectSection('EXPECT',array('Hello World'));       
    $testDifference = new rtTestDifference($expectSection, 'Hello Dolly');
    
    $difference = $testDifference->getDifference();
    $this->assertEquals('001+ Hello Dolly', $difference[0]);
    $this->assertEquals('001- Hello World', $difference[1]);
    }
  }
?>