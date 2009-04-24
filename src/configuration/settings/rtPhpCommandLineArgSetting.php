<?php

/**
 * Sets the arguments to teh PHP command line, initially as ini overrides
 * converted to -d flags
 *
 */
class rtPhpCommandLineArgSetting extends rtSetting
{
    private $phpCommandLineArguments;
    
    /**
     * Sets the command line arguments for
     *
     */
    public function init(rtRuntestsConfiguration $configuration)
    {
        $iniArguments = new rtIniAsCommandLineArgs();
        $iniArguments->setBase();
        $this->phpCommandLineArguments = $iniArguments->getBasePhpDArgs();  
        
        if ($configuration->hasCommandLineOption('n')) {
            $this->phpCommandLineArguments = '-n ' . $this->phpCommandLineArguments;
        }      
    }

    /**
     * Supply commandline arguments setting to the run configuration
     *
     */
    public function get()
    {
        return $this->phpCommandLineArguments;
    }
}
?>
