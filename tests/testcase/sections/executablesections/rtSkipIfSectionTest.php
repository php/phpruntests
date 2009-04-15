<?php
require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__) . '../../../../../src/rtAutoload.php';


class rtFileSectionTest extends PHPUnit_Framework_TestCase

{
public function testCreateInstance()  {
    $fileSection = new rtFileSection('FILE',array('<?php', 'echo "hello world";', '?>')); 
    $code = $fileSection->getContents(); 
    $this->assertEquals('<?php', $code[0]);
  }


}
?>