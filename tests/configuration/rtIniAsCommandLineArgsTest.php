<?php

require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__) . '../../../src/rtAutoload.php';

class rtIniAsCommandLineArgsTest extends PHPUnit_Framework_TestCase
{
  public function testPreSet() {
    $iniSet = new rtIniAsCommandLineArgs();
    $iniSet->setBase();
    $last49 = addslashes(substr($iniSet->getBasePhpDArgs(), -49));
    $this->assertEquals($last49, '-d \"unicode.from_error_mode=U_INVALID_SUBSTITUTE\"');
  }

  public function testAddFromArray() {
    $iniSet = new rtIniAsCommandLineArgs();
    $iniSet->setBase();
    $addStr = $iniSet->settingsToArguments(array('f=g'), $iniSet->getBasePhpDArgs());
    $last38= addslashes(substr($addStr, -38));
    $this->assertEquals($last38, 'or_mode=U_INVALID_SUBSTITUTE\" -d \"f=g\"');
  }

  public function testAddFromArray2() {
    $iniSet = new rtIniAsCommandLineArgs();
    $iniSet->setBase();
    $addStr = $iniSet->settingsToArguments(array('a='), $iniSet->getBasePhpDArgs());
    $last37= addslashes(substr($addStr, -37));
    $this->assertEquals($last37, 'or_mode=U_INVALID_SUBSTITUTE\" -d \"a=\"');
  }

  public function testExtraSpaces() {
    $iniSet = new rtIniAsCommandLineArgs();
    $iniSet->setBase();
    $addStr = $iniSet->settingsToArguments(array('a = f'), $iniSet->getBasePhpDArgs());
    $last37= addslashes(substr($addStr, -37));
    $this->assertEquals($last37, 'r_mode=U_INVALID_SUBSTITUTE\" -d \"a=f\"');
  }

  /**
   * @expectedException rtUnknownIniSettingException
   */
  public function testInvalidArg() {
    $iniSet = new rtIniAsCommandLineArgs();
    $iniSet->setBase();
    $addStr = $iniSet->settingsToArguments(array('af'), $iniSet->getBasePhpDArgs());
  }

  /**
   * @expectedException rtUnknownIniSettingException
   */
  public function testInvalidArg2() {
    $iniSet = new rtIniAsCommandLineArgs();
    $iniSet->setBase();
    $addStr = $iniSet->settingsToArguments(array('af=be=blah'), $iniSet->getBasePhpDArgs());
  }
}
?>


