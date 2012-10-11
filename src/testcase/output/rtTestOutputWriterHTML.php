<?php
/**
 * rtTestOutputWriterHTML
 *
 * Write test output in HTML
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
class rtTestOutputWriterHTML extends rtTestOutputWriter
{
	protected $dom = null;
	protected $stage = null;	// base-node which holds the content

	
	public function __construct()
	{
		$this->type = 'html';
		
		$docTitle = 'PHP RUN-TEST RESULTS';
		
		// dom
		$imp = new DOMImplementation();
		$dtd = $imp->createDocumentType("html", "-//W3C//DTD XHTML 1.0 Transitional//EN", "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd");
    	$this->dom = $imp->createDocument("", "", $dtd);

    	// html
    	$htmlNode = $this->dom->createElement('html');
    	$this->dom->appendChild($htmlNode);
    	
    	// head
    	$headNode = $this->dom->createElement('head');
    	$htmlNode->appendChild($headNode);
    	$headNode->appendChild($this->dom->createElement('title', $docTitle));
    	
    	// body
    	$bodyNode = $this->dom->createElement('body');
    	$htmlNode->appendChild($bodyNode);
    	
    	// stage
    	$this->stage = $this->dom->createElement('div');
    	$this->stage->setAttribute('id', 'stage');
    	$bodyNode->appendChild($this->stage);
    	
    	$this->stage->appendChild($this->dom->createElement('h1', $docTitle));
	}

    
    public function createOutput()
    {
        // table
    	$table = $this->dom->createElement('table');
    	$this->stage->appendChild($table);

    	// thead
    	$thead = $this->dom->createElement('thead');
    	$table->appendChild($thead);
    	$tr = $this->dom->createElement('tr');
    	$thead->appendChild($tr);
    	$tr->appendChild($this->dom->createElement('th', 'NAME'));
    	$tr->appendChild($this->dom->createElement('th', 'STATUS'));

    	
    	foreach ($this->resultList as $testGroupResults) {
    		
    		$tbody = $this->dom->createElement('tbody');
    		$table->appendChild($tbody);
        	
        	foreach ($testGroupResults as $testName => $testStatus) {

        		$tr = $this->dom->createElement('tr');
    			$tbody->appendChild($tr);

    			// name
    			$td = $this->dom->createElement('td', $testName);
    			$td->setAttribute('class', 'mainCol');
    			$tr->appendChild($td);

    			// status
    			
    			$s = $testStatus->__toString();
    			
    			$td = $this->dom->createElement('td', strtoupper($s));
    			$td->setAttribute('class', $s);
    			$tr->appendChild($td);
        	}
        }
        
        $this->dom->encoding = 'UTF-8';
        $this->dom->formatOutput = true;
    	$this->dom->normalizeDocument();
        $this->output = $this->dom->saveXML();
    }

}
?>