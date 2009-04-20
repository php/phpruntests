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
        if (is_null($configuration->getSetting('workingDirectory'))) {
            $workingDir = 'WORKING_DIR';
        } else {
            $workingDir = $configuration->getSetting('workingDirectory');
        }
        
        if ($configuration->hasEnvironmentVariable('TEST_PHP_CGI_EXECUTABLE')) {
            if($configuration->getEnvironmentVariable('TEST_PHP_CGI_EXECUTABLE') == 'auto') {
                $this->phpCgiExecutable = $workingDir . self::SAPI_CGI;
            } else {
                $this->phpCgiExecutable = $configuration->getEnvironmentVariable('TEST_PHP_CGI_EXECUTABLE');
            }
        } else {
            $this->phpCgiExecutable = null;
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
