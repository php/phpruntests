<?php


abstract class rtExecutableSection extends rtSection {
  
  protected $fileName;
  protected $output;
  protected $status;
  
  protected function init() {
    
  }
   
   protected function writeExecutableFile() {
    $contentsAsString = implode("\n", $this->sectionContents) ."\n";
    file_put_contents($this->fileName, (binary) $contentsAsString);
  }
  
  public function getFileName() {
    return $this->fileName;
  } 
  
  public function deleteFile() {
    @unlink($this->fileName);
  }
   
  public function getOutput() {
    return $this->output;
  }
  
  
  abstract function run(rtPhpTest $testcase, rtRuntestsConfiguration $runConfiguration);
}
?>
