<?php


/**
 * Class for the --XFAIL-- section
 *
 */
class rtXfailSection extends rtInformationSection {

  protected $failReason;

  protected function init() {
    //Only a single line reason is allowed. Ingore any more lines.
    if(isset($this->sectionContents[0])){
      $this->failReason = $this->sectionContents[0];
    } else {
      $this->failReason = "This test is apparently expected to fail but the author did not say why";
    }
  }

  public function getReason() {
    return $this->failReason;
  }
}
?>