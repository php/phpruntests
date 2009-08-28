<?php
/**
 * rtUnixConfiguration
 *
 * Class representing the run configuration for Unix
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
class rtUnixConfiguration extends rtRuntestsConfiguration
{
    public function __construct($cmdlineArgs)
    {
        $this->operatingSystem = 'Unix';
    	$this->commandLineArgs = $cmdlineArgs;
        $this->init();
    }
    
    /**
     * Adapts the configuration to Unix
     *
     */
    protected function adaptConfiguration()
    {
    }
}
?>
