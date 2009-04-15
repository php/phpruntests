<?php
class rtArgsSection extends rtConfigurationSection {

  private $testCommandLineArguments; 

  
  protected function init() {
    $this->testCommandLineArguments = '-- ' . $this->sectionContents[0];
  }
  
  /**
   * Return additional arguments to be added to the PHP Test command line
   *
   * @return string
   */
  public function getTestCommandLineArguments() {
    return $this->testCommandLineArguments;
  }

}
?>