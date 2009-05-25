<?php
/**
 * rtIsTestFileSpecified
 *
 * Class for checking that a test file, or directory has been specified
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
class rtIsTestFileSpecified extends rtPreCondition
{
    /**
     * Return the message associated with a missing PHP test file
     *
     * @return text
     */
    public function getMessage()
    {
        return rtText::get('missingTestFile');
    }

    /**
     * Check that a test file or directory has been given
     *
     * @param  rtRuntestsConfiguration $config
     * @return boolean
     */
    public function check(rtRuntestsConfiguration $config)
    {
    	if ($config->hasCommandLineOption('l')) {
            return true;
        }

        if ($config->hasCommandLineOption('r')) {
            return true;
        }

        if ($config->getTestFilename() != null) {
            return true;
        }

        if ($config->hasEnvironmentVariable('TEST_PHP_USER')) {
            return true;
        }

        return false;
    }
}
?>
