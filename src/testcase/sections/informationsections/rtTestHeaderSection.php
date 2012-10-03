<?php
/**
 * rtTestHeaderSection
 * 
 * Deals with information in test header (TEST) section
 *
 * @category  Testing
 * @package   RUNTESTS
 * @author    Zoe Slattery <zoe@php.net>
 * @author    Stefan Priebsch <spriebsch@php.net>
 * @copyright 2009 The PHP Group
 * @license   http://www.php.net/license/3_01.txt PHP License 3.01
 * @link      http://qa.php.net/
 */
class rtTestHeaderSection extends rtInformationSection
{
    protected $testHeader;
    
    protected function init()
    {
        //Only a single line heading is allowed. Ingore any more lines.
        $this->testHeader = $this->sectionContents[0];
    }
    
    public function getHeader()
    {
        return $this->testHeader;
    }
    
    public function addString($string) {
    	$this->testHeader .= $string;
    }
}
?>
