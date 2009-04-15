<?php
require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__) . '../../../../../src/rtAutoload.php';


class rtCleanSectionTest extends PHPUnit_Framework_TestCase

{
public function testCreateInstance()  {
    $cleanSection = new rtCleanSection('CLEAN',array('<?php', 'echo "hello world";', '?>')); 
    $code = $cleanSection->getContents(); 
    $this->assertEquals('<?php', $code[0]);
  }


}
?>