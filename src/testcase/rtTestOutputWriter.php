<?php
/**
 * rtTestOutputWriter
 *
 * Writes test output. This is concerned with status (PASS, FAIL etc) not
 * with the log files.
 *
 * @category   Testing
 * @package    RUNTESTS
 * @author     Zoe Slattery <zoe@php.net>
 * @author     Stefan Priebsch <spriebsch@php.net>
 * @copyright  2009 The PHP Group
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 *
 */
abstract class rtTestOutputWriter
{
    protected $testOutput;
    protected $testExitStatus;
    protected $testExitMessage;
    
    public static function getInstance (array $testResults, $outputType)
    {
        if ($outputType == 'list') {
            return new rtTestOutputWriterList($testResults);
        }
        
        if ($outputType == 'xml') {
            return new rtTestOutputWriterXML($testResults);
        }
        if ($outputType == 'csv') {
            return new rtTestOutputWriterCSV($testResults);
        }
    }  
    
    abstract function init(array $testResults);
    
    /**
     * Write the output to standard out
     *
     */
    abstract function write();
    
    /**
     * 
     */
    public function getOutput()
    {
        return $this->testOutput;
    }
}
?>
