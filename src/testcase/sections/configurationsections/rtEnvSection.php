<?php
class rtEnvSection extends rtConfigurationSection {

  private $testEnvironmentVariables = array();


  protected function init() {
    foreach($this->sectionContents as $line) {
      $tempArray = explode('=', $line);
      $this->testEnvironmentVariables[trim($tempArray[0])] = trim($tempArray[1]);
    }
  }

  /**
   * Additional environment variables required by the test
   *
   * @return array
   */
  public function getTestEnvironmentVariables() {
    return $this->testEnvironmentVariables;
  }
}
?>