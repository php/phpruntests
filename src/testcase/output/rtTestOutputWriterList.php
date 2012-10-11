<?php
/**
 * rtTestOutputWriterList
 *
 * Write test output line by line
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
class rtTestOutputWriterList extends rtTestOutputWriter
{
    public function __construct()
    {
         $this->type = 'txt';
    }

    
    public function createOutput()
    {
        foreach ($this->resultList as $testGroupResults) {
        	
        	foreach ($testGroupResults as $testName => $testStatus) {

	            $outputString = "";
	            
	            
	            foreach($testStatus->getTestStateNames() as $name) {

	            	if ($testStatus->getValue($name)) {
	                    $outputString .= " ". strtoupper($name);
	                    $outputString .= " " . $testStatus->getMessage($name);
	                }
	            }
	            
	            
	            $outputString .= " [" . $testName . ".phpt]";
	            $this->output .= $outputString."\n";
        	}
        }
    }

}
?>