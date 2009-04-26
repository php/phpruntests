<?php

/**
 * Class to set the directory at the start of the run
 *
 */
class rtCurrentDirectorySetting extends rtSetting
{
    private $currentDirectory;

    /**
     * Set the current directory
     *
     */
    public function init(rtRuntestsConfiguration $configuration)
    {
        $this->currentDirectory = realpath(getcwd());
    }

    /**
     * Return current directory setting to the configuration
     *
     */
    public function get()
    {
        return $this->currentDirectory;
    }
}
?>
