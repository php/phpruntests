<?php
/**
 * rtCurrentDirectorySetting
 *
 * Class to set the directory at the start of the run
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
class rtCurrentDirectorySetting extends rtSetting
{
    protected $currentDirectory;

    /**
     * Set the current directory
     *
     */
    public function init(rtRuntestsConfiguration $configuration)
    {
        $this->currentDirectory = realpath(getcwd());
    }

    /**
     * Return current directory setting to the configuration
     *
     */
    public function get()
    {
        return $this->currentDirectory;
    }
}
?>
