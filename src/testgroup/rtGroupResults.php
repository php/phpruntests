<?php
/**
 * rtPhpGroupResults
 *
 * Stores the results of  a 'group of tests'. A 'group' is all or the tests in a single directory.
 *
 * @category   Testing
 * @package    RUNTESTS
 * @author     Zoe Slattery <zoe@php.net>
 * @author     Stefan Priebsch <spriebsch@php.net>
 * @copyright  2009 The PHP Group
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 *
 */
class rtGroupResults 
{
    
    protected $testStatusList = array();
    protected $redirectedTestCases = array();
    protected $groupName;

    public function __construct($gn) {
    	$this->groupName = $gn;
    }
    
    public function __destruct() {
    	unset ($this->testStatusList);
    	unset ($this->redirectedTestCases);
    	
    }
    
    public function setTestStatus($name, $status) {
    	$this->testStatusList[$name] = $status;
    }
    
	public function setRedirectedTestCase (rtPhpTest $testCase) {
    	$this->redirectedTestCases[] = $testCase;
    }
    
    public function getGroupName() {
    	return $this->groupName;
    }
    
    public function getTestStatusList() {
    	return $this->testStatusList;
    }
    
    public function getRedirectedTestCases() {
    	return $this->redirectedTestCases;
    }
    
}