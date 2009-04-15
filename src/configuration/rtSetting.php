<?php

/**
 * Classes that set things in the test configuration
 *
 */


abstract class rtSetting {
  
  
  public function __construct(rtRuntestsConfiguration $configuration) {
    $this->init($configuration);
  }
  
  
  
  /**
   * Sets a variable from command line options or environmental variables
   *
   */
  abstract public function init(rtRuntestsConfiguration $configuration);
  
  
  /**
   * Applies the setting to the global environment
   *
   * @param rtGlobalEnvironment $env
   */
  abstract public function get();
  
}
?>