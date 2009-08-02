<?php
/**
 * rtExternalTool
 *
 * Class to handle using an external tool (default is valgrind)
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
 
class rtMemoryTool
{
    protected $command;
    protected $options;
    protected $version;
    
    public static function getInstance($configuration)
    {
        if($configuration->hasCommandLineOption('m')) {
            return new rtValgrind();            
        } else {
            $name = 'rt' . $configuration->getCommandLineOption('mtool');
            return new $name();
        }
    }
    
    public function init(rtRuntestsConfiguration $configuration) {
        $this->setVersion();
        $this->setCommand($configuration);
        $this->setOptions($configuration);
        $this->setEnvironment($configuration);
    }
    
    public function getCommand() {
        return $this->command;
    }
    
    
}
?>