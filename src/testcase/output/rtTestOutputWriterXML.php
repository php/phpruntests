<?php
/**
 * rtTestOutputWriterXML <singleton>
 *
 * Write test output as XML
 * 
 * @category   Testing
 * @package    RUNTESTS
 * @author     Zoe Slattery <zoe@php.net>
 * @author     Stefan Priebsch <spriebsch@php.net>
 * @author     Georg Gradwohl <g2@php.net>
 * @copyright  2009 The PHP Group
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link       http://qa.php.net/
 * 
 */
class rtTestOutputWriterXML extends rtTestOutputWriter
{
    private $dom = null;
	private $rootNode = null;
	private $stamp = 0;
	
	private static $instance = null;
	
	
	private function __construct() {

    	$this->dom = new DOMDocument();
    	$this->rootNode = $this->dom->createElement('RUNTESTS');
    	$this->dom->appendChild($this->rootNode);
    	
    	$this->stamp = round(microtime(true));
	}
    
    private function __clone() {}

    
    public static function getInstance()
    {
    	if (is_null(self::$instance)) {
    		self::$instance = new self;
    	}
    	
    	return self::$instance;
    }
    
    
    public function setTestResults(array $testResults)
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
        $dom = $this->dom;

        foreach ($testResults as $testResult) {

        	$test = $dom->createElement('testcase');
        	$test->appendChild($dom->createElement('name', $testResult->getName()));
        	
        	$status = 'UNDEFINED';
        	$testStatus = $testResult->getStatus();
        	
            foreach($testStatus->getTestStateNames() as $name) {

            	if ($testStatus->getValue($name)) {
                    $status = strtoupper($name);
                }
            }

        	$test->appendChild($dom->createElement('status', $status));
        	$this->rootNode->appendChild($test);
        }
    }


    public function write($testDirectory = null, $cid = null)
    {
        if (!is_null($this->dom)) {
        	
        	$xml = $this->dom->saveXML();
        	file_put_contents('results_'.$this->stamp.'.xml', $xml);
        }
    }
    
    
    /**
     * @Overrides src/testcase/rtTestOutputWriter#getOutput()
     */
    public function getOutput()
    {
    	if (!is_null($this->dom)) {
    		return $this->dom->saveXML();
    	}
    	
    	return null;
    }
}
?>
