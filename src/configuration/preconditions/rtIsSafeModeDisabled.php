<?php
/**
 * rtIsSafeModeDisabled
 *
 * Class for checking safe_mode is enabled
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
     * @param  rtRuntestsConfiguration $config
     * @return boolean
     */
    public function check(rtRuntestsConfiguration $config)
    {
        if (!ini_get('safe_mode')) {
            return true;
        }

        return false;
    }
}
?>
