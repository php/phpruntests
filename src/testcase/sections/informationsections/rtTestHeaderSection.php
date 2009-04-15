<?php

/**
 * Class for the --TEST-- section
 */
class rtTestHeaderSection extends rtInformationSection {
  
  protected $testHeader;
  
  protected function init() {
    //Only a single line heading is allowed. Ingore any more lines.
    $this->testHeader = $this->sectionContents[0];
  }
  
  public function getHeader() {
    return $this->testHeader;
  }
}
?>