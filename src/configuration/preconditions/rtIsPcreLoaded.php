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
     */
    public function check(rtRuntestsConfiguration $config)
    {
        return extension_loaded('pcre');
    }
}
?>
