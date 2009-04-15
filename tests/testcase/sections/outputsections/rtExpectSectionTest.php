<?php
require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__) . '../../../../../src/rtAutoload.php';


class rtExpectSectionTest extends PHPUnit_Framework_TestCase
{
public function testCreatePattern()  {
    $expectSection = new rtExpectSection('EXPECT',array('Hello World'));     
    $pattern = $expectSection->getPattern();
    $this->assertEquals('Hello World', $pattern);
  }


  public function testCreateTwolinePattern()  {
    $expectSection = new rtExpectSection('EXPECT',array('Hello World', 'Hello again')); 
     
    $pattern = $expectSection->getPattern();
    $this->assertEquals("Hello World\nHello again", $pattern);
  }

  public function testCreateTwolinePatternWithr()  {
    $expectSection = new rtExpectSection('EXPECT',array("Hello World\r", 'Hello again'));  
    $pattern = $expectSection->getPattern();
    $this->assertEquals("Hello World\nHello again", $pattern);
  }

  public function testCompare() {
    $expectSection = new rtExpectSection('EXPECT',array('Hello World') );
    $result = $expectSection->compare('Hello World');
    $this->assertTrue($result);
  }

}
?>