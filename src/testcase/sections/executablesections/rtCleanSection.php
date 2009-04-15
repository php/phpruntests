<?php

/**
 * Class representing the clean test section
 */
class rtCleanSection extends rtExecutableSection {


  public function setExecutableFileName($testName) {
    $this->fileName = $testName.".clean.php";
  }

  public function run(rtPhpTest $testCase, rtRuntestsConfiguration $runConfiguration) {
    
    $this->status = array();
    
    $this->setExecutableFileName($testCase->getName());
    $this->writeExecutableFile();
    
    $phpCommand =  $runConfiguration->getSetting('PhpExecutable');
    $phpCommand .= ' '.$runConfiguration->getSetting('PhpCommandLineArguments');
    $phpCommand .= ' -f '.$this->fileName;
    
    $PhpRunner = new rtPhpRunner($phpCommand,
           $runConfiguration->getEnvironmentVariables(), 
           $runConfiguration->getSetting('WorkingDirectory')
           );
    

    try {
      $this->output = $PhpRunner->runphp();
      //if the CLEAN section has worked the result should be a blank line
      if(trim($this->output) != "") {
        $this->status['warn'] = 'Execution of clean section failed: '.trim($this->output);
      } 
    } catch (rtPhpRunnerException $e) {
        $this->status['warn'] = 'Failed to execute clean section';
    }
    
    return $this->status;
  }
}

?>