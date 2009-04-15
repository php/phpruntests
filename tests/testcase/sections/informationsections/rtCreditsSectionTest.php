<?php
require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__) . '../../../../../src/rtAutoload.php';


class rtCreditsSectionTest extends PHPUnit_Framework_TestCase
{
  
public function testCreateInstance()  {
  
    $creditsSection = new rtCreditsSection('CREDITS',array('Test from Fred', 'PHP London test fest'));  
    $creditslist = $creditsSection->getContents();
    $this->assertEquals('Test from Fred', $creditslist[0]);
    $this->assertEquals('PHP London test fest', $creditslist[1]);
    
  }

}
?>