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
	
	
	public function __construct() {

		$this->type = 'xml';
		
    	$this->dom = new DOMDocument();
    	$this->rootNode = $this->dom->createElement('RUNTESTS');
    	$this->dom->appendChild($this->rootNode);
    	
    	$this->stamp = round(microtime(true));
	}


    /**
	 *
     * @param array of rtTestResults
     */
    public function createOutput()
    {
        foreach ($this->resultList as $result) {

        	$test = $this->dom->createElement('testcase');
        	$test->appendChild($this->dom->createElement('name', $result->getName()));
        	$test->appendChild($this->dom->createElement('status',  $result->getStatus()));
        	$this->rootNode->appendChild($test);
        }
        
        $this->output = $this->dom->saveXML();
    }



    

    
}
?>