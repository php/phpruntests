<?php
/**
 * rtTaskTestGroup
 *
 * @category   Testing
 * @package    RUNTESTS
 * @author     Zoe Slattery <zoe@php.net>
 * @author     Stefan Priebsch <spriebsch@php.net>
 * @author     Georg Gradwohl <g2@php.net>
 * @copyright  2009 The PHP Group
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 *
 */
class rtTaskTestGroup extends rtTask implements rtTaskInterface
{
	protected $runConfiguration;
	protected $subDirectory;
	protected $groupConfig;

	
	public function __construct($runConfiguration, $subDirectory, $groupConfig)
	{
		$this->runConfiguration = $runConfiguration;
		$this->subDirectory = $subDirectory;
		$this->groupConfig = $groupConfig;
	}
	
	
	/**
	 * called by the child-process
	 * executes the the test-group
	 */
	public function run()
	{
		$testGroup = new rtPhpTestGroup($this->runConfiguration, $this->subDirectory, $this->groupConfig);
		$testGroup->run();
        $this->result = $testGroup->getGroupResults();
              
        $testGroup->__destruct();
        unset($testGroup);
        	
		return true;
	}
	
	public function getSubDirectory() {
	    return $this->subDirectory;
	}
	

}


?>