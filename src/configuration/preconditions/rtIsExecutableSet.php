<?php

/**
 * Class for checking whether a PHP Executable has been set
 *
 */
class rtIsExecutableSet extends rtPreCondition
{
    /**
     * Return the message associated with a missing PHP executable
     *
     * @return text
     */
    public function getMessage()
    {
        return rtText::get('missingPHPExecutable');
    }
    
    
    /**
     * Check that the PHP executable is a valid executable
     *
     * @param  rtRuntestsConfiguration $config
     * @return boolean
     * @access public
     */
    public function check(rtRuntestsConfiguration $config)
    {
        if ($config->hasCommandLineOption('p')) {
            return true;
        }

        if ($config->hasEnvironmentVariable('TEST_PHP_EXECUTABLE')) {
            return true;
        }

        return false;
    }
    
    
    /*
    public function check(rtCommandLineOptions $commandLine = null, rtEnvironmentVariables $environmentVariables = null)
    {
        if ($commandLine->hasOption('p')) {
            return true;
        }

        if ($environmentVariables->hasVariable('TEST_PHP_EXECUTABLE')) {
            return true;
        }

        return false;
    }
    */  
}
?>
