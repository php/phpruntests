<?php
/**
 * rtCleanSection
 * 
 * Executes the code in the --CLEAN-- section
 *
 * @category  Testing
 * @package   RUNTESTS
 * @author    Zoe Slattery <zoe@php.net>
 * @author    Stefan Priebsch <spriebsch@php.net>
 * @copyright 2009 The PHP Group
 * @license   http://www.php.net/license/3_01.txt PHP License 3.01
 * @link      http://qa.php.net/
 */
class rtCleanSection extends rtExecutableSection
{
    public function setExecutableFileName($testName)
    {
        $this->fileName = $testName.".clean.php";
    }

    public function run(rtPhpTest $testCase, rtRuntestsConfiguration $runConfiguration)
    {
        $testStatus = $testCase->getStatus();
        
        $this->setExecutableFileName($testCase->getName());
        $this->writeExecutableFile();
        
        $phpCommand = $runConfiguration->getSetting('PhpExecutable');
        $phpCommand .= ' '.$runConfiguration->getSetting('PhpCommandLineArguments');
        $phpCommand .= ' -f '.$this->fileName;
        
        $PhpRunner = new rtPhpRunner($phpCommand,
            $runConfiguration->getEnvironmentVariables(), 
            $runConfiguration->getSetting('WorkingDirectory')
        );
        
        try {
            $this->output = $PhpRunner->runphp();
            //if the CLEAN section has worked the result should be a blank line
            if (trim($this->output) != "") {
                $testStatus->setTrue('fail_clean');
                $testStatus->setMessage('fail_clean','Execution of clean section failed: '.trim($this->output) );
        
            } 
        } catch (rtException $e) {
            $testStatus->setTrue('fail_clean');
            $testStatus->setMessage('fail_clean',$e->getMessage);
        }
        
        return $testStatus;
    }
}
?>
