<?php
/**
 * rtSkipIfSection
 *
 * @category  Testing
 * @package   RUNTESTS
 * @author    Zoe Slattery <zoe@php.net>
 * @author    Stefan Priebsch <spriebsch@php.net>
 * @copyright 2009 The PHP Group
 * @license   http://www.php.net/license/3_01.txt PHP License 3.01
 * @link      http://qa.php.net/
 */

/**
 * Executes the code in the --SKIPIF-- section
 *
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
        $this->status = array();
        
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

            if (!strncasecmp('skip', ltrim($this->output), 4)) {
                if (preg_match('/^\s*skip\s*(.+)\s*/i', $this->output, $matches)) {
                    $this->status['skip'] = $matches[1];
                } else {
                    $this->status['skip'] = '';
                }
            }

            if (!strncasecmp('warn', ltrim($this->output), 4)) {
                if (preg_match('/^\s*warn\s*(.+)\s*/i', $this->output, $matches)) {
                    $this->status['warn'] = $matches[1];
                } else {
                    $this->status['warn'] = '';
                }
            }
        } catch (rtPhpRunnerException $e) {
            $this->status['bork'] = 'Failed to execute skipif section';
        }
        
        return $this->status;
    }
}
?>
