<?php
  require_once 'PHPUnit/Framework.php';
 require_once dirname(__FILE__) . '../../../../src/rtAutoload.php';


  class rtCommandLineArgSettingTest extends PHPUnit_Framework_TestCase {
    
   public function testSet() {
     
    $configuration = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', 'a-php-executable'));
    
    $clArgsetting  = new rtPhpCommandLineArgSetting($configuration);
    
    $last49 = addslashes(substr($clArgsetting->get(), -49));
    $this->assertEquals('-d \"unicode.from_error_mode=U_INVALID_SUBSTITUTE\"', $last49);
    
    }
  }
?>