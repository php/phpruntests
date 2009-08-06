<?php
/**
 * rtPhpCommandLineArgSetting
 *
 * Sets the arguments to the PHP command line.
 * Initially as ini overrides,  converted to -d flags
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
class rtPhpCommandLineArgSetting extends rtSetting
{
    protected $phpCommandLineArguments;
    
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
