<?php

/**
 * Sets the format of the log file
 *
 */
class rtLogFormatSetting extends rtSetting {

  private $logFormat;


  /**
   * Sets log format to that specifiled by TEST_PHP_LOG_FORMAT or LEOD
   *
   */
  public function init(rtRuntestsConfiguration $configuration) {

    if ($configuration->hasEnvironmentVariable('TEST_PHP_LOG_FORMAT')) {
      $this->logFormat = strtoupper($configuration->getEnvironmentVariable('TEST_PHP_LOG_FORMAT'));
    } else {
      $this->logFormat = 'LEOD';

    }
  }


  /**
   * Apply log format setting to the configuration
   *
   * 
   */
  public function get() {
    return $this->logFormat;
  }

}

?>
