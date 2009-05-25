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

    protected function init()
    {
    }
     
    protected function writeExecutableFile()
    {    
        // @todo I think \n could be replaced with PHP_EOL here - need to check on Windows.   
        $contentsAsString = implode("\n", $this->sectionContents) . PHP_EOL;
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

    abstract function run(rtPhpTest $testcase, rtRuntestsConfiguration $runConfiguration);
}
?>
