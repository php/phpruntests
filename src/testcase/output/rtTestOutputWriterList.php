<?php
/**
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
            ksort($testStatus);
            //Status can be PASS and WARN or FAIL and WARN. I think these are the olny two combinations 
            //but there may be more
            foreach ($testStatus as $status => $message) {
                $outputString .= strtoupper($status);
                $outputString .= " " . $message;
            }
            $outputString .= " " . $testResult->getTitle();
            $outputString .= " [" . $testResult->getName() . ".phpt]";
            $this->testOutput[] = $outputString;
        }
    }


    public function write($testDirectory = null)
    {
        if ($testDirectory != null) {
            echo "\n\nTest output for tests in " . $testDirectory . "\n";
        }
        sort($this->testOutput);
        foreach ($this->testOutput as $line) {
            echo $line ."\n";
        }
    }
}
?>
