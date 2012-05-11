<?php
/**
 * rtPhpCgiSetting
 *
 * Class for setting the PHP CGI executable name
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
class rtPhpCgiExecutableSetting extends rtSetting
{
    const SAPI_CGI = "/sapi/cgi/php-cgi";

    protected $phpCgiExecutable;

    protected $configuration;

    /**
     * Sets the PHP CGI executable. Note the dependency on having a working directory setting
     *
     */
    public function init(rtRuntestsConfiguration $configuration)
    {
        $this->configuration = $configuration;

        if ($configuration->hasEnvironmentVariable('TEST_PHP_CGI_EXECUTABLE')) {
            if($configuration->getEnvironmentVariable('TEST_PHP_CGI_EXECUTABLE') != null) {
                if ($configuration->getEnvironmentVariable('TEST_PHP_CGI_EXECUTABLE') == 'auto') {

                    $rtWorkingDirectorySetting = new rtWorkingDirectorySetting($configuration);
                    $this->phpCgiExecutable = $rtWorkingDirectorySetting->get() . self::SAPI_CGI;
                } else {
                    $this->phpCgiExecutable = $configuration->getEnvironmentVariable('TEST_PHP_CGI_EXECUTABLE');
                }
            }
        }
    }

    /**
     * If the path tp the php cli executable is either:
     * 				something/cli/php 
     * or, failing that, 
     * 				something/php
     * we can make a guess at the path to the cgi executable
     */
    private function guessFromPhpCli($phpCli)
    {
    	
    	if(substr($phpCli, -7) === "cli/php") {
    		$cgiGuess = substr($phpCli, 0, -7) . "cgi/php-cgi";    		
    	}elseif( substr($phpCli, -3) === "php") {
    		$cgiGuess = substr($phpCli, 0, -3) . "php-cgi";  		
    	}else{
    		$cgiGuess = null;
    	}
    	
    	return $cgiGuess;
    }

    /**
     * Returns path to PHP CGI executable.
     * If not set, we guess based on the path to the PHP CLI executable.
     *
     * @return string
     */
    public function get()
    {
        if (is_null($this->phpCgiExecutable)) {

            // We ask rtPhpExecutableSetting for the path to the PHP executable.
            $rtPhpExecutableSetting = new rtPhpExecutableSetting($this->configuration);
            $phpCli=$rtPhpExecutableSetting->get();
            $this->phpCgiExecutable = $this->guessFromPhpCli($phpCli);
           
        }
        return $this->phpCgiExecutable;
    }
}
?>
