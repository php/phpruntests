<?php

/**
 * Class for setting the test directory (or directories) - these contain PHPT files 
 * to be tested and can be provided on teh command line or by  TEST_PHP_USER
 *
 */
class rtTestDirectorySetting extends rtSetting
{
    private $testDir = null;
    
    /**
     * Check each option - if it's a directory add it to the list.
     * If not leave it.
     *
     * @param rtCommandLine $commandLine
     * @param rtEnvironmentVariables $environmentVariables
     */
    public function init(rtRuntestsConfiguration $configuration)
    {
        $fileArray = $configuration->getTestFilename();

        foreach ($fileArray as $file) {
            if (is_dir($file)) {
                $this->testDir[]= $file;
            }
        }

        if ($configuration->hasEnvironmentVariable('TEST_PHP_USER')) {
            $fileArray = trim($configuration->getEnvironmentVariable('TEST_PHP_USER'));
            foreach ($fileArray as $file) {
                if (is_dir($file)) {
                    $this->testDir[]= $file;
                }
            }
        }
    }

    /**
     * Set the test directory/directories list in the configuration
     * 
     */
    public function get()
    {
        return $this->testDir;
    }  
}
?>
