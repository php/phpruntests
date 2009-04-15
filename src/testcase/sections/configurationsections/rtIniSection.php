<?php

class rtIniSection extends rtConfigurationSection {

  private $commandLineArguments = array();


  public function init() {
    foreach ($this->sectionContents as $line) {
      $this->commandLineArguments[] = addslashes($line);
    }
  }
  
  /**
   * Returns any additional PHP commandline arguments
   *
   * @return array
   */
  public function getCommandLineArguments() {
    return $this->commandLineArguments;
  }

}
?>