<?php

/**
 * A pre-condtion for running a test
 */
class rtPreCondition {

  
  /**
   * The message to use if teh pre-condition is not met
   *
   */
  public function getMessage() {
  }

  /**
   * Code to check the pre-condition
   *
   * @return boolean
   */
  public function check(rtCommandLineOptions $commandLine = null, rtEnvironmentVariables $environmentVariables = null) {
    return true;
  }

}
?>