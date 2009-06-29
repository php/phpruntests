<?php
/**
 * rtTestOutputWriterList
 *
 * Write test output line by line to stdout
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
class rtTestOutputWriterList extends rtTestOutputWriter
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
            $outputString = "";
            $testStatus = $testResult->getStatus();
            foreach($testStatus->getTestStateNames() as $name) {
                if($testStatus->getValue($name)) {
                    $outputString .= " ". strtoupper($name);
                    $outputString .= " " . $testStatus->getMessage($name);
                }
            }
            $outputString .= " " . $testResult->getTitle();
            $outputString .= " [" . $testResult->getName() . ".phpt]";
            $this->testOutput[] = $outputString;
        }
    }


    public function write($testDirectory = null,  $cid = null)
    {
        if ($testDirectory != null) {
            echo "\n\nTest output for tests in " . $testDirectory . "\n";
        }
        sort($this->testOutput);
        foreach ($this->testOutput as $line) {
        	
             if (!is_null($cid)) {
        		echo "$cid - ";
        	}
        	
            echo $line ."\n";
        }
    }
}
?>
