<?php
/**
 * rtIsPcreLoaded
 *
 * Class for checking whether the PCRE extension is loaded
 * 
 * @category   Testing
 * @package    RUNTESTS
 * @author     Zoe Slattery <zoe@php.net>
 * @author     Stefan Priebsch <spriebsch@php.net>
 * @copyright  2009 The PHP Group
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link       http://qa.php.net/
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
