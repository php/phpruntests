<?php
/**
 * rtWinConfiguration
 *
 * Windows specific configuration
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
class rtWinConfiguration extends rtRuntestsConfiguration
{
    public function __construct($cmdlineArgs)
    {
        $this->operatingSystem = 'Windows';
    	$this->commandLineArgs = $cmdlineArgs;
        $this->init();
    }

    /**
     * Adapt the environmnet to Windows
     *
     */
    protected function adaptConfiguration()
    {
    }
}
?>
