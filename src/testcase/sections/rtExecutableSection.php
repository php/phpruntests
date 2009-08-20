<?php
/**
 * rtExecutableSection
 * 
 * Parent class for executable sections
 *
 *
 * @category  Testing
 * @package   RUNTESTS
 * @author    Zoe Slattery <zoe@php.net>
 * @author    Stefan Priebsch <spriebsch@php.net>
 * @copyright 2009 The PHP Group
 * @license   http://www.php.net/license/3_01.txt PHP License 3.01
 * @link      http://qa.php.net/
 */
abstract class rtExecutableSection extends rtSection
{
    protected $fileName;
    protected $output;
    protected $status;
    protected $phpCommand = null;

    protected function init()
    {
    }
     
    protected function writeExecutableFile()
    {    
        // Don't even think anout replacing the \n with PHP_EOL   
        // It causes stuff (ext/phar/tests/005.phpt) on windows.
        $contentsAsString = implode("\n", $this->sectionContents) . "\n";
        file_put_contents($this->fileName,  (binary) $contentsAsString);
    }

    public function getFileName()
    {
        return $this->fileName;
    }

    public function deleteFile()
    {
        @unlink($this->fileName);
    }
     
    public function getOutput()
    {
        return $this->output;
    }

    public function getPhpCommand()
    {
    	return $this->phpCommand;
    }

    abstract function run(rtPhpTest $testcase, rtRuntestsConfiguration $runConfiguration);
}
?>
