<?php
/**
 * rtIniAsCommandLineArgs
 *
 * Class to handle overrides of ini settings in run-tests
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
class rtIniAsCommandLineArgs
{
    protected $iniOverwrites = array(
        'output_handler=',
        'open_basedir=',
        'safe_mode=0',
        'disable_functions=',
        'output_buffering=Off',
        "error_reporting= 32767",
        'display_errors=1',
        'display_startup_errors=1',
        'log_errors=0',
        'html_errors=0',
        'track_errors=1',
        'report_memleaks=1',
        'report_zend_debug=0',
        'docref_root=',
        'docref_ext=.html',
        'error_prepend_string=',
        'error_append_string=',
        'auto_prepend_file=',
        'auto_append_file=',
        'magic_quotes_runtime=0',
        'ignore_repeated_errors=0',
        'unicode.runtime_encoding=ISO-8859-1',
        'unicode.script_encoding=UTF-8',
        'unicode.output_encoding=UTF-8',
        'unicode.from_error_mode=U_INVALID_SUBSTITUTE',
    );

    protected $basePhpDArgs;

    /**
     * 
     */
    public function setBase()
    {
        $this->basePhpDArgs = $this->settingsToArguments($this->iniOverwrites);
    }

    /**
     * Sets the -d flags used in the PHP command that executes the PHP
     * code from a test case
     * @param array - ini settings
     * @param string - command line arguments to be extended
     * @return string - command line arguments
     *
     */
    public function settingsToArguments($array, $args="")
    {
        foreach ($array as $setting) {
            if ($this->isValidSetting($setting)) {
                $setting = $this->stripSpaces($setting);
                $args .= " -d \"$setting\"";
            } else {
            	//TODO ext/libxml/tests/bug61367-read.phpt
            	// and ext/libxml/tests/bug61367-write.phpt
            	// introduce a comment mid-section which triggers 
            	// this exception. Fix tests or doc?
                //throw new rtException("Invalid INI setting $setting");
            }
        }

        return $args;
    }

    /**
     * Checks that the ini setting is valid
     *
     * @param string - ini setting in the form a=b
     * @return boolean - false if not in expected format
     */
    public function isValidSetting($string)
    {
        $parts = explode("=", $string, 2);

        //explode failed
        if ($parts[0] === $string) {
            return false;
        }
        return true;
    }

    /**
     * Removes spaces from ini settings, "a  =  b" is returned as "a=b"
     *
     * @param string - ini setting
     * @return string - ini setting with any spaces removed
     */
    public function stripSpaces($string)
    {
        $noSpace = "";
        $parts = explode("=", $string, 2);
        $noSpace .= trim($parts[0]) . "=" . trim($parts[1]);

        return $noSpace;
    }

    /**
     * Returns an array containing the PHP test execution flags
     *
     * @return unknown
     */
    public function getBasePhpDArgs()
    {
        return $this->basePhpDArgs;
    }
}
?>
