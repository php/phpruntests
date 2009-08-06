<?php
/**
 * rtIsPhpVersionCorrect
 * 
 * class for checking the required minimum-version (5.3)
 * of the the running and executing php-executable
 *
 * @category  Testing
 * @package   RUNTESTS
 * @author    Zoe Slattery <zoe@php.net>
 * @author    Stefan Priebsch <spriebsch@php.net>
 * @author    Georg Gradwohl <g2@php.net>
 * @copyright 2009 The PHP Group
 * @license   http://www.php.net/license/3_01.txt PHP License 3.01
 * @link      http://qa.php.net/
 */
class rtIsPhpVersionCorrect extends rtPreCondition
{
    /**
     * @var int
     */
    protected $requiredMajorVersion = 5;

    /**
     * @var int
     */
    protected $requiredMinorVersion = 3;

    /**
     * Return the message associated with an invalid php-version
     *
     * @return text
     */
    public function getMessage()
    {
        return rtText::get('invalidPhpVersion');
    }  

    /**
     * check that used php-version matches the precondition
     *
     * @param rtRuntestsConfiguration $config
     * 
     * @return boolean
     */
    public function check(rtRuntestsConfiguration $config)
    {
        $exec = escapeshellcmd($config->getSetting('PhpExecutable').' -v');

        $pipe = popen($exec, "r");
        $output = fread($pipe, 1024);
        pclose($pipe);
            
        return $this->parseVersionString($output);
    }

    /**
     * parses the version-string and checks the required minimun-version
     *
     * @param string $versionStr output of "php -v"
     * 
     * @return boolean
     */
    public function parseVersionString($versionStr)
    {
        $major = substr($versionStr, 4, 1);
        $minor = substr($versionStr, 6, 1);

        if ($major > $this->requiredMajorVersion) {
            return true;

        } elseif ($major == $this->requiredMajorVersion) {

            if ($minor >= $this->requiredMinorVersion) {
                return true;        			
            }
       }

        return false;
    }
}
?>
