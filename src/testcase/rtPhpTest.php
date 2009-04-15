<?php


class rtPhpTest {

  private $testName;
  public $testConfiguration;
  private $contents;
  private $status;
  private $output;
  private $sections;
  private $fileSection;
  private $expectSection;
  private $sectionHeadings;


  public function __construct(array $contents, $testName, $sectionHeadings, $runConfiguration) {
    $this->contents = $contents;
    $this->testName = $testName;
    $this->sectionHeadings = $sectionHeadings;
    $this->parse();
    $this->init($runConfiguration);
  }

  public function parse() {

    for ($i=0; $i<count($this->contents); $i++) {
      //Create an array of section objects
      if($this->isSectionKey($this->contents[$i])) {
        $sectionKey = $this->contents[$i];

        $tempArray = array();
        for($j=$i+1; $j<count($this->contents); $j++) {
           
          if ($this->isSectionKey($this->contents[$j])) {
            $testSection = rtSection::getInstance($sectionKey, $tempArray);
            $this->sections[$sectionKey] = $testSection;
            break;
          }
          $tempArray[] = $this->contents[$j];
        }
      }
    }
    $testSection = rtSection::getInstance($sectionKey, $tempArray);
    $this->sections[$sectionKey] = $testSection;

    //Identify the file and expect section types
    $this->fileSection = $this->setFileSection();
    $this->expectSection = $this->setExpectSection();


  }


  public function init(rtRuntestsConfiguration $runConfiguration) {
    $this->testConfiguration = new rtTestConfiguration($runConfiguration, $this->sections);
  }


  //run
  public function executeTest(rtRuntestsConfiguration $runConfiguration) {

    $this->status = array();

    if(array_key_exists('SKIPIF', $this->sections)) {
      $this->status = $this->sections['SKIPIF']->run($this, $runConfiguration);
    }

    if(!array_key_exists('skip', $this->status) && !array_key_exists('bork', $this->status)) {
      $this->status = array_merge($this->status, $this->fileSection->run($this, $runConfiguration));
      $this->output = $this->fileSection->getOutput();
      $this->compareOutput();
       

      if(array_key_exists('CLEAN', $this->sections)) {
        $cleanStatus = $this->sections['CLEAN']->run($this, $runConfiguration);
        $this->status = array_merge($this->status, $cleanStatus);
      }

    }

  }

  public function compareOutput() {

    $result = $this->expectSection->compare($this->output);

    if($result) {
      $this->status['pass'] = '';
    } else {
      $this->status['fail'] = '';
    }
  }

  private function isSectionKey($line) {
    if(in_array($line, $this->sectionHeadings)) {
      return true;
    }
    return false;
  }


  private function setFileSection() {
    if(array_key_exists('FILE', $this->sections)) {
      return $this->sections['FILE'];
    }

    if(array_key_exists('FILEEOF', $this->sections)) {
      return $this->sections['FILEEOF'];
    }

    if(array_key_exists('FILE_EXTERNAL', $this->sections)) {
      return $this->sections['FILE_EXTERNAL'];
    }
  }

  private function setExpectSection() {
    if(array_key_exists('EXPECT', $this->sections)) {
      return $this->sections['EXPECT'];
    }

    if(array_key_exists('EXPECTF', $this->sections)) {
      return  $this->sections['EXPECTF'];
    }

    if(array_key_exists('EXPECTREGEX', $this->sections)) {
      return $this->sections['EXPECTREGEX'];
    }

  }



  public function getName() {
    return $this->testName;
  }

  public function getOutput() {
    return $this->output;
  }

  public function hasSection($sectionKey) {
    return array_key_exists($sectionKey, $this->sections);
  }

  public function getSection($sectionKey) {
    return $this->sections[$sectionKey];
  }

  public function getStatus() {
    return $this->status;
  }

  public function getFileSection() {
    return $this->fileSection;
  }

  public function getExpectSection() {
    return $this->expectSection;
  }


}
?>
