<?php
/**
 * rtFileSection
 * Executes the code in the --FILE-- section
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
class rtFileSection extends rtExecutableSection
{
    protected $twoBlankLines = '\r?\n\r?\n';
    protected $headers;
    protected $memFileName;

    protected function init() {
        $this->fileName = $this->testName . ".php";
        $this->memFileName = $this->testName . ".mem";
    }

    public function run(rtPhpTest $testCase, rtRuntestsConfiguration $runConfiguration)
    {
         
        $testStatus = $testCase->getStatus();
        $this->writeExecutableFile();

        $commandPrefix = "";
        if($runConfiguration->hasMemoryTool()) {
            $commandPrefix = $runConfiguration->getMemoryToolCommand();
            //This assumes that the external tool write its output to a *.mem file
            $commandPrefix .= $this->memFileName;
        }

        $phpExecutable = $testCase->testConfiguration->getPhpExecutable();

        // The CGI excutable is null if it is not available, check and SKIP if necessary
        if (is_null($phpExecutable)) {
            $testStatus->setTrue('skip');
            $testStatus->setMessage('skip', 'The CGI executable is unavailable' );
            return $testStatus;
        }


        $phpCommand = $commandPrefix . " " . $phpExecutable;
        $phpCommand .= ' '. $testCase->testConfiguration->getPhpCommandLineArguments();
        $phpCommand .= ' -f '.$this->fileName;
        $phpCommand .= ' '.$testCase->testConfiguration->getTestCommandLineArguments();
        $phpCommand .= ' 2>&1 '.$testCase->testConfiguration->getInputFileString();

        $this->phpCommand = $phpCommand;

        $PhpRunner = new rtPhpRunner($phpCommand,
        $testCase->testConfiguration->getEnvironmentVariables(),
        $runConfiguration->getSetting('WorkingDirectory'),
        $testCase->testConfiguration->getStdin()
        );

        try {
            $this->output = $PhpRunner->runphp();

            //If it's a CGI test and separate the headers from the output
            if($testCase->testConfiguration->isCgiTest()) {
                // Would this be better done with substr/strpos, not sure how to cope with \n
                // Do Web servers alsways send \n\r\n\r? I *think* so but need to check

                if (preg_match("/^(.*?)$this->twoBlankLines(.*)/s", $this->output, $match)) {
                    $this->output = $match[2];
                    $this->headers = $match[1];
                }
            }


        } catch (rtException $e) {
            $testStatus->setTrue('fail');
            $testStatus->setMessage('fail', $e->getMessage() );
        }

         
        if($runConfiguration->hasMemoryTool()) {
            if(filesize($this->memFileName) > 0) {
                $testStatus->setTrue('leak');
            } else {
                $this->deleteMemFile();
            }
        }

        return $testStatus;
    }

    /**
     *
     */
    public function getHeaders()
    {
        return $this->headers;
    }


    public function deleteMemFile()
    {
        @unlink($this->memFileName);
    }


    public function getMemFileName()
    {
        return $this->memFileName;
    }


}
?>
