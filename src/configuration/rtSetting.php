<?php
/**
 * rtSetting
 * 
 * Classes that set things in the test configuration.
 * Settings may instantiate other settings to retrieve values they need.
 * This leads to some settings being instantiated multiple times, and
 * being redundantly calculated, but makes settings independent from the order 
 * in which they are processed in rtRuntestsConfiguration.
 * 
 * @category  Testing
 * @package   RUNTESTS
 * @author    Zoe Slattery <zoe@php.net>
 * @author    Stefan Priebsch <spriebsch@php.net>
 * @copyright 2009 The PHP Group
 * @license   http://www.php.net/license/3_01.txt PHP License 3.01
 * @link      http://qa.php.net/
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
