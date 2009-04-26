<?php

/**
 * Class for setting the PHP CGI executable
 *
 */
class rtPhpCgiExecutableSetting extends rtSetting
{
    const SAPI_CGI = "/sapi/cgi/php";

    private $phpCgiExecutable;
    
    /**
     * Sets the PHP CGI executable. Note the dependency on having a working directory setting
     *
     */
    public function init(rtRuntestsConfiguration $configuration)
    {
      
        
        if ($configuration->hasEnvironmentVariable('TEST_PHP_CGI_EXECUTABLE')) {
            if ($configuration->getEnvironmentVariable('TEST_PHP_CGI_EXECUTABLE') == 'auto') {
                $this->phpCgiExecutable = $configuration->getSetting('WorkingDirectory') . self::SAPI_CGI;
            } else {
                $this->phpCgiExecutable = $configuration->getEnvironmentVariable('TEST_PHP_CGI_EXECUTABLE');
            }
        } else {
            $this->phpCgiExecutable = null;
        } 
    }
    
    /**
     * 
     */
    public function setFromPhpCli($phpCli)
    {
        if(substr(dirname($phpCli),-3) == 'cli') {
            $pathLength = strlen(dirname($phpCli)) - 3;
            $sapiDir = substr(dirname($phpCli), 0, $pathLength);          
            $this->phpCgiExecutable = $sapiDir."cgi/php";
        }
    }
    
    
    /**
     * Supply the setting to the configuration on request
     *
     */
    public function get() 
    {
        return $this->phpCgiExecutable;
    }  
} 
?>
