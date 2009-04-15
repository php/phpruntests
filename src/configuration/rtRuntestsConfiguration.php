<?php

/**
 * Contains onfiguration for test run. Includes:
 * Command line option, environment variables, ini overrides and settings which are
 * derived from one or more command line options or environment variables.
 *
 */

abstract class rtRuntestsConfiguration {

  private $settings;
  private $setters;
  private $environmentVariables;
  private $commandLine;
  protected $commandLineArgs;

  private $settingNames = array (

      'CurrentDirectory' => 'rtCurrentDirectorySetting',
      'LogFormat' => 'rtLogFormatSetting',
      'PhpExecutable' => 'rtPhpExecutableSetting',
      'PhpCgiExecutable' => 'rtPhpCgiExecutableSetting',
      'TestFiles' => 'rtTestFileSetting',
      'TestDirectories' => 'rtTestDirectorySetting',
      'WorkingDirectory' => 'rtWorkingDirectorySetting',
      'PhpCommandLineArguments' => 'rtPhpCommandLineArgSetting',

  );


  protected function init() {
    //parse command line
    $this->commandLine = new rtCommandLineOptions;
    $this->commandLine->parse($this->commandLineArgs);

    //get and put envrionment variables
    $this->environmentVariables = rtEnvironmentVariables::getInstance();
    $this->environmentVariables->getUserSuppliedVariables();
  }


  public function configure() {

    //extend test command line using TEST_PHP_ARGS
    $options = new rtAddToCommandLine();
    $options->parseAdditionalOptions($this->commandLine, $this->environmentVariables);

    //check configuration preconditions
    $preConditionList = rtPreConditionList::getInstance();
    $preConditionList->check($this->commandLine, $this->environmentVariables);


    //set configuration
    foreach($this->settingNames as $name => $setting) {
      $this->setters[$name] = new $setting($this);
    }


    foreach($this->settingNames as $name => $setting) {
      $methodName = 'set' . $name;
      $this->$methodName();
    }

  }


  /**
   * Factory: returns rtRuntestsConfiguration subclass for the given os.
   *
   * @returns rtEnvironment
   */
  public static function getInstance ($commandLineArgs, $os = 'Unix')
  {
    if ($os == 'Windows') {
      return new rtWinConfiguration($commandLineArgs);
    } else {
      return new rtUnixConfiguration($commandLineArgs);
    }
  }


  /**
   * Sets the directory that run-tests was started from
   *
   */
  private function setCurrentDirectory() {
    $this->settings['CurrentDirectory']= $this->setters['CurrentDirectory']->get();
  }


  /**
   * Sets the directory that run-tests is run from
   *
   */
  private function setWorkingDirectory() {
    $this->settings['WorkingDirectory']= $this->setters['WorkingDirectory']->get();
  }


  /**
   * Sets the PHP executable being used to run teh tests
   *
   */
  private function setPhpExecutable() {
    $this->settings['PhpExecutable']= $this->setters['PhpExecutable']->get();

  }


  /**
   * Sets the PHP GGI executable being used to run the tests
   *
   */
  private function setPhpCgiExecutable() {
    $this->settings['PhpCgiExecutable']= $this->setters['PhpCgiExecutable']->get();

  }


  /**
   * Sets the log format
   *
   */
  private function setLogFormat() {
    $this->settings['LogFormat']= $this->setters['LogFormat']->get();
  }


  /**
   * Sets the command line arguments for PHP
   *
   */
  private function setPhpCommandLineArguments() {
    $this->settings['PhpCommandLineArguments']= $this->setters['PhpCommandLineArguments']->get();
  }


  /**
   * Sets the names of directories to be tested
   *
   */
  private function setTestDirectories() {
    $this->settings['TestDirectories'] = $this->setters['TestDirectories']->get();
  }


  /**
   * Sets the names of files to be tested
   *
   * @param array $testFiles
   */
  private function setTestFiles() {
    $this->settings['TestFiles'] = $this->setters['TestFiles']->get();
  }


  /**
   * Returns the value of a setting
   *
   * @param string $settingName
   * @param mixed (may be array or string)
   */

  public function getSetting($settingName) {
    return $this->settings[$settingName];
  }


  /**
   * Returns the entire settings array
   *
   * @return array $settings
   */
  public function getSettings() {
    return $this->settings;
  }

  public function getEnvironmentVariable($name) {
    return $this->environmentVariables->getVariable($name);
  }
  
  public function getEnvironmentVariables() {
    return $this->environmentVariables->getVariables();
  }

  public function hasCommandLineOption($option) {
    return $this->commandLine->hasOption($option);
  }

  public function getCommandLineOption($option) {
    return $this->commandLine->getOption($option);
  }

 
  public function hasEnvironmentVariable($name) {
    return $this->environmentVariables->hasVariable($name);
  }

  /**
   * required for testing?
   *
   * @param unknown_type $name
   * @param unknown_type $value
   * @return unknown
   */
  public function setEnvironmentVariable($name, $value) {
    $this->environmentVariables->setVariable($name, $value);
  }
  
  public function getTestFilename() {
    return $this->commandLine->getTestFilename();
  }


}


?>
