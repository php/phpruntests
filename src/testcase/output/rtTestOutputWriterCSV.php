<?php
/**
 * rtTestOutputWriterCSV
 *
 * Write minimal testoutput and status a CSV
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
class rtTestOutputWriterCSV extends rtTestOutputWriter
{
    protected $testOutput = array();

    public function __construct(array $testResults)
    {
        $this->init($testResults);
    }

    /**
     *
     *
     * @param array of rtTestResults
     *
     */
    public function init (array $testResults)
    {
        foreach ($testResults as $testResult) {
            $outputString = $testResult->getName();
            $testStatus = $testResult->getStatus();
            foreach($testStatus->getTestStateNames() as $name) {
                if($testStatus->getValue($name)) {
                    $outputString .= " , ". strtoupper($name);
                    
                }
            }
            $this->testOutput[] = $outputString;
        }
    }


    public function write($testDirectory = null)
    {
        sort($this->testOutput);
        foreach ($this->testOutput as $line) {
            echo $line ."\n";
        }
    }
}
?>