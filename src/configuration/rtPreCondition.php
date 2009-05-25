<?php
/**
 * rtPreCondtion
 *
 * Parent class for run pre-condidtions
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
class rtPreCondition
{
    /**
     * The message to use if the pre-condition is not met
     *
     */
    public function getMessage()
    {
    }

    /**
     * Code to check the pre-condition
     *
     * @return boolean
     */
    public function check(rtRuntestsConfiguration $config)
    {
        return true;
    }
}
?>
