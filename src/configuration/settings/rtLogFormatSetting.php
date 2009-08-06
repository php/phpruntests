<?php
/**
 * rtLogFormatSetting
 *
 * Determines which log files ae written. 
 * L=log, E=exp, O=out, D=diff
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
/*
 * @todo don't have anything implemented that checks this yet
 */
class rtLogFormatSetting extends rtSetting
{
    protected $logFormat;

    /**
     * Sets log format to that specifiled by TEST_PHP_LOG_FORMAT or LEOD
     *
     */
    public function init(rtRuntestsConfiguration $configuration)
    {
        if ($configuration->hasEnvironmentVariable('TEST_PHP_LOG_FORMAT')) {
            $this->logFormat = strtoupper($configuration->getEnvironmentVariable('TEST_PHP_LOG_FORMAT'));
        } else {
            $this->logFormat = 'LEOD';
        }
    }

    /**
     * Apply log format setting to the configuration
     *
     * 
     */
    public function get()
    {
        return $this->logFormat;
    }
}
?>
