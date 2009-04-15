<?php

/**
 * Class for checking that a test file, or directory has been specified
 *
 */
class rtIsTestFileSpecified extends rtPreCondition {

  
   /**
   * Return the message associated with a missing PHP test file
   *
   * @return text
   */
  public function getMessage() {
    return rtText::get('missingTestFile');
  }
  
  
   /**
   * Check that a test file or directory has been given
   *
   * @param rtCommandLine $commandLine
   * @param rtEnvironmentVariables $environmentVariables
   * @return boolean
   */
  public function check(rtCommandLineOptions $commandLine = null, rtEnvironmentVariables $environmentVariables = null) {
    if($commandLine->hasOption('l')) {
      return true;
    }
    if($commandLine->hasOption('r')) {
      return true;
    }
    if($commandLine->getTestFilename() != null) {
      return true;
    }
    if($environmentVariables->hasVariable('TEST_PHP_USER')) {
      return true;
    }
    return false;
  }
  
}
?>