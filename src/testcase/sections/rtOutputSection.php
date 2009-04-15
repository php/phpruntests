<?php

/**
 * parent class for all expected output sections
 *
 */

abstract class rtOutputSection extends rtSection {

  protected $expectedPattern;
  
  protected function init() {
    $this->createPattern();
  }
  
  /**
   * Create the pattern used to match against actual output
   *
   */
  protected function createPattern() {
    $this->expectedPattern = implode ($this->lineFeed, $this->sectionContents );
    $this->expectedPattern = str_replace($this->carriageReturnLineFeed, $this->lineFeed, $this->expectedPattern);

    //remove any blank lines from the start and end
    $this->expectedPattern = trim($this->expectedPattern);
  }

  public function getPattern() {
    return $this->expectedPattern;
  }

  abstract function compare($testOutput);
}
?>
