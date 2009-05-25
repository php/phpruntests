<?php
/**
 * rtIsExecutableSet
 *
 * Class for checking whether a PHP Executable has been set
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
class rtIsExecutableSet extends rtPreCondition
{
    /**
     * Return the message associated with a missing PHP executable
     *
     * @return text
     */
    public function getMessage()
    {
        return rtText::get('missingPHPExecutable');
    }
    
    
    /**
     * Check that the PHP executable is a valid executable
     *
     * @param  rtRuntestsConfiguration $config
     * @return boolean
     */
    public function check(rtRuntestsConfiguration $config)
    {
        if ($config->hasCommandLineOption('p')) {
            return true;
        }

        if ($config->hasEnvironmentVariable('TEST_PHP_EXECUTABLE')) {
            return true;
        }

        return false;
    }
}
?>
