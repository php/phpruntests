<?php
/**
 * rtIsProcOpenAvailable
 *
 * Class for checking whether proc_open() is available
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
class rtIsProcOpenAvailable extends rtPreCondition
{
    /**
     * Return the message associated with missing proc_open();
     *
     * @return text
     */
    public function getMessage()
    {
        return rtText::get('procOpenNotAvailable');
    }

    /**
     * Check that proc_open() is available
     *   
     * @param  rtRuntestsConfiguration $config
     * @return boolean
     */
    public function check(rtRuntestsConfiguration $config)
    {
    	return function_exists('proc_open');
    }
}
?>
