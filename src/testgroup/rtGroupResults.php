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
    protected $timeToRun;
    protected $runOrder;
    protected $runByProcessor = 0;
    protected $absTime;
    protected $isSkipGroup = false;

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
    public function setTime($t) {
    	$this->timeToRun = $t;
    }
    
	public function setAbsTime($t) {
    	$this->absTime = $t;
    }
    
    public function setCount($c) {
    	
    	$this->runOrder = $c;
    }
    
    public function setProc($p) {
    	$this->runByProcessor = $p;
    }
    public function setSkip($s) {
    	$this->isSkipGroup = $s;
    }
    
    public function getTime() {
    	return $this->timeToRun;
    }
    
    public function getRunOrder() {
    	return $this->runOrder;
    }
    
    public function getProcessorId() {
    	return $this->runByProcessor;
    }
	public function getAbsTime() {
    	return $this->absTime;
    }
    public function isSkipGroup() {
    	return $this->isSkipGroup;
    }
    
    
}