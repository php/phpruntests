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
    private $twoBlankLines = '\r?\n\r?\n';
    private $headers;

    public function setExecutableFileName($testName)
    {
        $this->fileName = $testName.".php";
    }

    protected function init() {
    }

    public function run(rtPhpTest $testCase, rtRuntestsConfiguration $runConfiguration)
    {
        $testStatus = $testCase->getStatus();
        $this->writeExecutableFile();

        $phpExecutable = $testCase->testConfiguration->getPhpExecutable();

        // The CGI excutable is null if it is not available, check and SKIP if necessary
        if (is_null($phpExecutable)) {
            $testStatus->setTrue('skip');
            $testStatus->setMessage('skip', 'The CGI executable is unavailable' );         
            return $testStatus;
        }


        $phpCommand = $phpExecutable;
        $phpCommand .= ' '. $testCase->testConfiguration->getPhpCommandLineArguments();
        $phpCommand .= ' -f '.$this->fileName;
        $phpCommand .= ' '.$testCase->testConfiguration->getTestCommandLineArguments();
        $phpCommand .= ' 2>&1 '.$testCase->testConfiguration->getInputFileString();
         

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


        } catch (rtPhpRunnerException $e) {
            $testStatus->setTrue('fail');
            $testStatus->setMessage('fail', $e->getMessage() );         
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


}
?>
