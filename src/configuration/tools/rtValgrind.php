<?php
/**
 * rtvalgrind
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


class rtValgrind extends rtMemoryTool
{
    public function checkAvailable($configuration)
    {
        $p = new rtIsValgrindAvailable();
        if(!$p->check($configuration)) {
            throw new rtException($p->getMessage());
        }
    }
    

    public function setVersion() {
        $phpRunner = new rtPhpRunner('valgrind --version');
        $valgrindheader = $phpRunner->runPHP();
        $this->version =  preg_replace("/valgrind-([0-9])\.([0-9])\.([0-9]+)([.-]\w+)?(\s+)/", '$1$2$3', $valgrindheader, 1, $replace_count);
    }

    public function setCommand() {
            $this->command = "valgrind -q --tool=memcheck --trace-children=yes";         
    }
    
    public function setOptions($configuration) {
        $options = "";
        if($configuration->hasCommandLineOption('mopts')) {
            $options = preg_replace('/\"/', '', $configuration->getCommandLineOption('mopts'));
        }
        
        if($this->version >= 330) {
            $this->command .= " " . $options . " --log-file=";
        } else {
             $this->command .= " " . $options . " --log-file-exactly=";
        }
    }
    
    public function setEnvironment($configuration) {
        $configuration->setEnvironmentVariable('USE_ZEND_ALLOC', '0');
    }
}
?>