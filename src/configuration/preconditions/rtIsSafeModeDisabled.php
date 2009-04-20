<?php

/**
 * Class for checking if safe_mode is enabled
 *
 */
class rtIsSafeModeDisabled extends rtPreCondition
{
    /**
     * Return the message associated with safe mode enabled
     *
     * @return text
     */
    public function getMessage()
    {
        return rtText::get('safeModeNotDisabled');
    }

    /**
     * Check to see if safe mode is enabled
     *
     * @return boolean
     */
    public function check(rtCommandLineOptions $commandLine = null, rtEnvironmentVariables $environmentVariables = null)
    {
        if (!ini_get('safe_mode')) {
            return true;
        }

        return false;
    }
}
?>
