<?php
/**
 * rtSkipIfSection
 * 
 *  Executes the code in the --SKIPIF-- section
 *
 * @category  Testing
 * @package   RUNTESTS
 * @author    Zoe Slattery <zoe@php.net>
 * @author    Stefan Priebsch <spriebsch@php.net>
 * @copyright 2009 The PHP Group
 * @license   http://www.php.net/license/3_01.txt PHP License 3.01
 * @link      http://qa.php.net/
 */
class rtSkipIfSection extends rtExecutableSection
{
    public function setExecutableFileName($testName)
    {
        $this->fileName = $testName.".skipif.php";
    }

    public function run(rtPhpTest $testCase, rtRuntestsConfiguration $runConfiguration)
    {
        
        $testStatus = $testCase->getStatus();
        $this->setExecutableFileName($testCase->getName());
        $this->writeExecutableFile();
        
        $phpExecutable = $testCase->testConfiguration->getPhpExecutable();

        // The CGI excutable is null if it is not available, check and SKIP if necessary
        if (is_null($phpExecutable)) {
            $testStatus->setTrue('skip');
            $testStatus->setMessage('skip', 'The CGI executable is unavailable' );         
            return $testStatus;
        }

        $phpCommand = $phpExecutable;
        $phpCommand .= ' '.$testCase->testConfiguration->getPhpNonFileSectionCommandLineArguments();
        $phpCommand .= ' -f '.$this->fileName;
        
        $this->phpCommand = $phpCommand;
                
        $PhpRunner = new rtPhpRunner($phpCommand,
            $runConfiguration->getEnvironmentVariables(), 
            $runConfiguration->getSetting('WorkingDirectory')
        );

        try {
            $this->output = $PhpRunner->runphp();
       
            if (!strncasecmp('skip', ltrim($this->output), 4)) {
                 $testStatus->setTrue('skip');
                if (preg_match('/^\s*skip\s*(.+)\s*/i', $this->output, $matches)) {         
                    $testStatus->setMessage('skip', $matches[1]);
                }
            }

            if (!strncasecmp('warn', ltrim($this->output), 4)) {
                 $testStatus->setTrue('warn');
                if (preg_match('/^\s*warn\s*(.+)\s*/i', $this->output, $matches)) {
                    $testStatus->setMessage('warn', $matches[1]);
                }
            }
        } catch (rtException $e) {
            $testStatus->setTrue('fail_skip');
            $testStatus->setMessage('fail_skip', 'Failed to execute skipif section' . $e->getMessage());
        }
        
        return $testStatus;
    }
}
?>
