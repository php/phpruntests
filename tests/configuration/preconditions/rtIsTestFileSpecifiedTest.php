<?php
require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__) . '../../../../src/rtAutoload.php';


class rtIsTestFileSpecifiedTest extends PHPUnit_Framework_TestCase
  {
    public function testClOptionR() {
    $env = rtEnvironmentVariables::getInstance();
    $clo = new rtCommandLineOptions();
    $clo->parse(array('run-tests.php', '-r', 'some-file'));

    $pre = new rtIsTestFileSpecified();
    $this->assertTrue($pre->check($clo,$env));
  }
  public function testCLOptionL() {
    $env = rtEnvironmentVariables::getInstance();
    $clo = new rtCommandLineOptions();
    $clo->parse(array('run-tests.php', '-l', 'some-file'));

    $pre = new rtIsTestFileSpecified();
    $this->assertTrue($pre->check($clo,$env));
  }
  public function testCLOptionFileName() {
    $env = rtEnvironmentVariables::getInstance();
    $clo = new rtCommandLineOptions();
    $clo->parse(array('run-tests.php', 'some-test-file'));

    $pre = new rtIsTestFileSpecified();
    $this->assertTrue($pre->check($clo,$env));
  }
  public function testEnvVar() {
    $env = rtEnvironmentVariables::getInstance();
    $clo = new rtCommandLineOptions();
    $env->setvariable('TEST_PHP_USER','some-file');

    $pre = new rtIsTestFileSpecified();
    $this->assertTrue($pre->check($clo,$env));
  }
  public function testNotSpecified() {
    $env = rtEnvironmentVariables::getInstance();
    $clo = new rtCommandLineOptions();
    
    $pre = new rtIsTestFileSpecified();
    $this->assertFalse($pre->check($clo,$env));
  }
  public function testGetText() {
    $pre = new rtIsTestFileSpecified();
    $this->assertEquals($pre->getMessage('missingTestFile'),rtText::get('missingTestFile'));
  }
  
}
?>