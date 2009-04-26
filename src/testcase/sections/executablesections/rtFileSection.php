<?php
/**
 * Class for the --FILE-- section
 */
class rtFileSection extends rtExecutableSection
{
    public function setExecutableFileName($testName)
    {
        $this->fileName = $testName.".php";
    }

    public function run(rtPhpTest $testCase, rtRuntestsConfiguration $runConfiguration)
    {
        $this->status = array();

        $this->setExecutableFileName($testCase->getName());
        $this->writeExecutableFile();

        $phpExecutable = $testCase->testConfiguration->getPhpExecutable();
        
        // The CGI excutable is null if it is not available, check and SKIP if appropriate
        if ($phpExecutable != null) {
            $phpCommand = $phpExecutable;
            $phpCommand .= ' '.$testCase->testConfiguration->getPhpCommandLineArguments();
            $phpCommand .= ' -f '.$this->fileName;
            $phpCommand .= ' '.$testCase->testConfiguration->getTestCommandLineArguments();

            $PhpRunner = new rtPhpRunner($phpCommand,
            $testCase->testConfiguration->getEnvironmentVariables(),
            $runConfiguration->getSetting('WorkingDirectory')
            );

            try {
                $this->output = $PhpRunner->runphp();
            } catch (rtPhpRunnerException $e) {
                $this->status['fail'] = $e->getMessage();
            }
        } else {
            $this->status['skip'] = 'The CGI executable is unavailable';
        }

        return $this->status;
    }
}
?>
