<?php
/**
 * rtPhpExecutableSetting
 *
 * Class to set the PHP executable to be tested.
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
class rtPhpExecutableSetting extends rtSetting
{
    const SAPI_CLI =  "/sapi/cli/php";

    protected $phpExecutable;

    /**
     * Sets the PHP executable, note the dependency on working directory
     *
     */
    public function init(rtRuntestsConfiguration $configuration)
    {
        if ($configuration->hasEnvironmentVariable('TEST_PHP_EXECUTABLE')) {

            if ($configuration->getEnvironmentVariable('TEST_PHP_EXECUTABLE') == 'auto') {

                $rtWorkingDirectorySetting = new rtWorkingDirectorySetting($configuration);
                $this->phpExecutable = $rtWorkingDirectorySetting->get() . self::SAPI_CLI;
            } else {
                $this->phpExecutable = $configuration->getEnvironmentVariable('TEST_PHP_EXECUTABLE');
            }
        }

        if ($configuration->hasCommandLineOption('p')) {
            $this->phpExecutable = $configuration->getCommandLineOption('p');
        }
    }

    /**
     * Supply the PHP executable setting to the configuration
     *
     */
    public function get()
    {
        return $this->phpExecutable;
    }
}
?>
