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
    		
	        foreach ($testGroupResults as $testResult) {
	
	        	// create test-node
	        	$testNode = $this->dom->createElement('testcase');
	        	$groupNode->appendChild($testNode);

	        	// name
	        	$n = explode($wdir, $testResult->getName());
	        	$n = explode('/', $n[1]);
    			$n = array_pop($n);
    			$testNode->setAttribute('name', $n);
				
    			// status
    			$status = $testResult->getStatus();
    			$s = $status->__toString();
				$testNode->setAttribute('status', strtoupper($s));
		
	        	// title
        	    $title = $testResult->getTitle();

    			if (strlen($title) > 0) {
    				$titleNode = $this->dom->createElement('title', utf8_encode(htmlentities($title)));
    				$testNode->appendChild($titleNode);
    			}
				
				// message
        	    $msg = $status->getMessage($s);

    			if (!is_null($msg)) {
    				$msgNode = $this->dom->createElement('message', utf8_encode(htmlentities($msg)));
    				$testNode->appendChild($msgNode);
    			}
    			
    			// files
    			$files = $testResult->getSavedFileNames();
					
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

	    	// add group-node attributes

    		$n = explode($wdir, $testGroupResults[0]->getName());
    		$n = explode('/', $n[1]);
    		array_pop($n);
    		$n = implode('/', $n);
    		
    		$groupNode->setAttribute('name', $n);
    		$groupNode->setAttribute('tests', sizeof($testGroupResults));
    		
    		$time = round($testGroupResults[0]->getTime(), 2);
    		$groupNode->setAttribute('time', $time);
	        
    		foreach ($state as $k => $v) {
	    		$groupNode->setAttribute($k, $v);
	    	}
    	}
        
    	$this->dom->encoding = 'UTF-8';
    	$this->dom->formatOutput = true;
    	$this->dom->normalizeDocument();
        $this->output = $this->dom->saveXML();
    }

}
?>