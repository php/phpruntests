<?php

/**
 * Classes that set things in the test configuration.
 * Settings may instantiate other settings to retrieve values they need.
 * This leads to some settings being instantiated multiple times, and
 * being redundantly calculated, but makes settings independent from the order 
 * in which they are processed in rtRuntestsConfiguration.
 *
 */
abstract class rtSetting
{
    public function __construct(rtRuntestsConfiguration $configuration)
    {
        $this->init($configuration);
    }
    
    /**
     * Sets a variable from command line options or environmental variables
     *
     */
    abstract public function init(rtRuntestsConfiguration $configuration);
        
    /**
     * Applies the setting to the global environment
     *
     * @param rtGlobalEnvironment $env
     */
    abstract public function get();  
}
?>
