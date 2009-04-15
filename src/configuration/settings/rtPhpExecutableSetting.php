<?php

/**
 * Class to set the PHP executable to be tested
 *
 */
class rtPhpExecutableSetting extends rtSetting{
  
  private $phpExecutable;
  
  const SAPI_CLI =  "/sapi/cli/php";
 
  
  /**
   * Sets the PHP executable, note the dependency on working directory
   *
   */
  public function init(rtRuntestsConfiguration $configuration) {
    
   if(is_null($configuration->getSetting('workingDirectory'))) {
      $workingDir = 'WORKING_DIR';
    } else {
      $workingDir = $configuration->getSetting('workingDirectory');
    }
    
  if ($configuration->hasEnvironmentVariable('TEST_PHP_EXECUTABLE')) {
      if($configuration->getEnvironmentVariable('TEST_PHP_EXECUTABLE') == 'auto') {
        $this->phpExecutable = $workingDir.self::SAPI_CLI;

      } else {
        $this->phpExecutable = $configuration->getEnvironmentVariable('TEST_PHP_EXECUTABLE');
      }
    }
    if ($configuration->hasCommandLineOption('p')) {
      $this->phpExecutable = $configuration->getCommandLineOption('p');
    }
  }
  
  
  /**
   * Supply the PHP executable setting to the configuration
   *
   */
  public function get() {
    return $this->phpExecutable;
  }
  
}

?>
