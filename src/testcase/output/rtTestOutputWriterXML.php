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
    protected $dom = null;
	protected $rootNode = null;
	
	
	public function __construct()
	{
		$this->type = 'xml';
		
    	$this->dom = new DOMDocument();
    	$this->rootNode = $this->dom->createElement('RUNTESTS');
    	$this->dom->appendChild($this->rootNode);
	}


    /**
	 *
     * @param array of rtTestResults
     */
    public function createOutput()
    {
    	$wdir = getcwd();
    	
    	$global_state = array();
    	$global_count = 0;
    	
    	foreach ($this->resultList as $testGroupResults) {
			
    		// creat group-node
    		$groupNode = $this->dom->createElement('testgroup');
    		$this->rootNode->appendChild($groupNode);

    		$state = array();
    		
	        foreach ($testGroupResults as $testName => $testStatus) {
	
	        	// create test-node
	        	$testNode = $this->dom->createElement('testcase');
	        	$groupNode->appendChild($testNode);

	        	// name
	        	$n = explode($wdir, $testName);
	        	$n = explode('/', $n[1]);
    			$n = array_pop($n);
    			$testNode->setAttribute('name', $n);
				
    			// status
    			
    			$s = $testStatus->__toString();
				$testNode->setAttribute('status', strtoupper($s));
	
				
				// message
        	    $msg = $testStatus->getMessage($s);

    			if (!is_null($msg)) {
    				$msgNode = $this->dom->createElement('message', utf8_encode(htmlentities($msg)));
    				$testNode->appendChild($msgNode);
    			}
    			
    			// files
    			$files = $testStatus->getSavedFileNames();
					
				if (sizeof($files) > 0) {
					
					$fileNode = $this->dom->createElement('files');
    				$testNode->appendChild($fileNode);
					
					foreach ($files as $type => $file) {
						$fileNode->setAttribute($type, $file);
					}
				}
				
				// count
				if (!isset($state[$s])) {
					$state[$s] = 0;
					$global_state[$s] = 0;
				}

				$state[$s]++;
				$global_state[$s]++;
	    	}
	    	
	    	$global_count += sizeof($testGroupResults);

    	}
        
    	$this->dom->encoding = 'UTF-8';
    	$this->dom->formatOutput = true;
    	$this->dom->normalizeDocument();
        $this->output = $this->dom->saveXML();
    }

}
?>