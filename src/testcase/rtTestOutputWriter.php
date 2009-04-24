<?php
/**
 * Class for writing the output from a test
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
            return new rtTestOutputWriterXML($testresults);
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
