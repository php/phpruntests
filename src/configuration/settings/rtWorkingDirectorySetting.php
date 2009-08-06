<?php
/**
 * rtWorkingDirectorySetting
 *
 * Class for setting the working directory (may be different to 
 * the directory that run-tests.php was started from)
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
class rtWorkingDirectorySetting extends rtSetting
{
    protected $workingDirectory;
    
    /**
     * Sets the working directory
     *
     */
    public function init(rtRuntestsConfiguration $configuration)
    {
        if ($configuration->hasEnvironmentVariable('TEST_PHP_SRCDIR')) {
            $this->workingDirectory = $configuration->getEnvironmentVariable('TEST_PHP_SRCDIR');
        } else {
            $this->workingDirectory = getcwd();
        }
    }
    
    /**
     * Supply working directory to the configuration
     *
     */
    public function get()
    {
        return $this->workingDirectory;
    }  
}
?>
