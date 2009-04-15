<?php
require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__) . '../../../../src/rtAutoload.php';


class rtIfParallelHasPcntlTest extends PHPUnit_Framework_TestCase
{
  public function testLoaded() {        
    $clo = new rtCommandLineOptions();
    $clo->parse(array('run-tests.php', '-z'));
    $env = rtEnvironmentVariables::getInstance();
    
    $pre = new rtIfParallelHasPcntl();
    $this->assertTrue($pre->check($clo, $env));
  }
  
  public function testLoaded2() {        
    $clo = new rtCommandLineOptions();
    $env = rtEnvironmentVariables::getInstance();
    $env->setVariable('TEST_PHP_PARALLEL', true);
    
    $pre = new rtIfParallelHasPcntl();
    $this->assertTrue($pre->check($clo, $env));
  }
  
  public function testNotRequired() {        
    $clo = new rtCommandLineOptions();
    $env = rtEnvironmentVariables::getInstance();
    
    $pre = new rtIfParallelHasPcntl();
    $this->assertTrue($pre->check($clo, $env));
  }
  public function testgetMessage() {     
    $pre = new rtIfParallelHasPcntl();
    $this->assertEquals($pre->getMessage('pcntlNotLoaded'), rtText::get('pcntlNotLoaded'));
  }
  
  //Not sure how to check if it's not loaded?
}
?>