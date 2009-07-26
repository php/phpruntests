<?php
/**
 * rtIsValgrindAvailable
 *
 * Class for checking whether the PCNTL extension is loaded.
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
class rtIsValgrindAvailable extends rtPreCondition
{
    /**
     * Return the message associated with missing Valgrind
     *
     * @return text
     */
    public function getMessage()
    {
        return rtText::get('valgrindNotAvailable');
    }

    /**
     * Check that the right version of valgrind is available
     *
     * @param  rtRuntestsConfiguration $config
     * @return boolean
     */
    public function check(rtRuntestsConfiguration $config)
    {
        if ($config->hasCommandLineOption('m')) {
            $valgrind_cmd = "valgrind --version";
            $phpRunner = new rtPhpRunner($valgrind_cmd);
            $valgrind_header = $phpRunner->runPHP();
             
             
            if (!$valgrind_header) {
                //valgrind not available
                return false;
            } else {
                $replace_count = 0;
                $valgrind_version = preg_replace("/valgrind-([0-9])\.([0-9])\.([0-9]+)([.-]\w+)?(\s+)/", '$1$2$3', $valgrind_header, 1, $replace_count);
                if ($replace_count != 1 || !is_numeric($valgrind_version)) {
                    //Valgrind returned invalid version info
                    return false;
                }
            }
        }
        return true;
    }
}
?>