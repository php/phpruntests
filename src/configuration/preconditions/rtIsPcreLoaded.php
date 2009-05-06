<?php
/**
 * Class for checking whether the PCRE extension is loaded
 *
 */
class rtIsPcreLoaded extends rtPreCondition
{
    /**
     * Return the message associated with missing PCRE
     *
     * @return text
     */
    public function getMessage()
    {
        return rtText::get('pcreNotLoaded');
    }  

    /**
     * Check that the PCRE is loaded
     *    
     * @param  rtRuntestsConfiguration $config
     * @return boolean
     * @access public
     */
    public function check(rtRuntestsConfiguration $config)
    {
        return extension_loaded('pcre');
    }
    
    
    /*
    public function check(rtCommandLineOptions $commandLine = null, rtEnvironmentVariables $environmentVariables = null)
    {
        if (extension_loaded('pcre')) {
            return true;
        }

        return false;
    }
    */
}
?>
