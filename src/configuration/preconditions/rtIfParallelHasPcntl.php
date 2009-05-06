<?php

/**
 * Class for checking whether the PCNTL extension is loaded
 *
 */
class rtIfParallelHasPcntl extends rtPreCondition
{
    /**
     * Return the message associated with missing PCRE
     *
     * @return text
     */
    public function getMessage()
    {
        return rtText::get('pcntlNotLoaded');
    }

    /**
     * Check that the PCNTL is loaded if parallel run is required
     *
     * @param  rtRuntestsConfiguration $config
     * @return boolean
     * @access public
     */
    public function check(rtRuntestsConfiguration $config)
    {
        if ($config->hasCommandLineOption('z') || $config->hasEnvironmentVariable('TESTS_PHP_PARALLEL')) {
            return extension_loaded('pcntl');
        }

        return true;
    }
    
    
    /*
    public function check(rtCommandLineOptions $commandLine = null, rtEnvironmentVariables $environmentVariables = null)
    {
        if ($commandLine->hasOption('z') || $environmentVariables->hasVariable('TESTS_PHP_PARALLEL')) {
            return extension_loaded('pcntl');
        }

        return true;
    }
    */
}
?>
