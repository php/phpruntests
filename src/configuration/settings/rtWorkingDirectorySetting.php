<?php

/**
 * Class for setting the working directory (may be different to the directory 
 * that run-tests.php was started from)
 *
 */
class rtWorkingDirectorySetting extends rtSetting
{
    private $workingDirectory;
    
    /**
     * Sets the working directory
     *
     */
    public function init(rtRuntestsConfiguration $configuration)
    {
        if ($configuration->hasEnvironmentVariable('TEST_PHP_SRCDIR')) {
            $this->workingDirectory = $configuration->getEnvironmentVariable('TEST_PHP_SRCDIR');
        } else {
            $this->workingDirectory = getcwd();
        }
    }
    
    /**
     * Supply working directory to the configuration
     *
     */
    public function get()
    {
        return $this->workingDirectory;
    }  
}
?>
