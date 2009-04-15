<?php
/**
 * Needs to set up teh configuration fo rthe test. This comes from
 * 	1. RuntestsConfiguration
 *  2. Sections which modify the Runtests configuration
 */

class rtTestConfiguration {

   private $environmentVariables;
   private $phpCommandLineArguments;
   private $testCommandLineArguments;

  public function __construct(rtRuntestsConfiguration $runConfiguration, $sections) {
    $this->init($runConfiguration, $sections);
  }

  private function init(rtRuntestsConfiguration $runConfiguration, $sections) {
    $this->setEnvironmentVariables($runConfiguration, $sections);
    $this->setPhpCommandLineArguments($runConfiguration, $sections);
    $this->setTestCommandLineArguments($sections);
  }

  private function setEnvironmentVariables(rtRuntestsConfiguration $runConfiguration, $sections) {
    $this->environmentVariables = $runConfiguration->getEnvironmentVariables();
    if(array_key_exists('ENV', $sections)) {    
      $this->environmentVariables = array_merge($this->environmentVariables, $sections['ENV']->getTestEnvironmentVariables());
    }
  }

  private function setPhpCommandLineArguments(rtRuntestsConfiguration $runConfiguration, $sections) {
    $this->phpCommandLineArguments = $runConfiguration->getSetting('PhpCommandLineArguments');
    if(array_key_exists('INI', $sections)) {
      $additionalArguments = $sections['INI']->getCommandLineArguments();
      $args = new rtIniAsCommandLineArgs();
      $this->phpCommandLineArguments = $args->settingsToArguments($additionalArguments, $this->phpCommandLineArguments);
    }
  }
  
  private function setTestCommandLineArguments($sections) {
  $this->testCommandLineArguments = '';
    if(array_key_exists('ARGS', $sections)) {
      $this->testCommandLineArguments = $sections['ARGS']->getTestCommandLineArguments();
    }
  }
  
  public function getEnvironmentVariables() {
    return $this->environmentVariables;
  }
  
  public function getPhpCommandLineArguments() {
    return $this->phpCommandLineArguments;
  }
  
  public function getTestCommandLineArguments() {
    return $this->testCommandLineArguments;
  }
   
}
?>