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
	private $runConfiguration;
	private $subDirectory;
	
	
	public function __construct($runConfiguration, $subDirectory)
	{
		$this->runConfiguration = $runConfiguration;
		$this->subDirectory = $subDirectory;
	}
		
	public function run()
	{
		$testGroup = new rtPhpTestGroup($this->runConfiguration, $this->subDirectory);
		$testGroup->runGroup($this->runConfiguration);
		
		$outType = 'list';
        if ($this->runConfiguration->hasCommandLineOption('o')) {           		
            		$outType = $this->runConfiguration->getCommandLineOption('o');
        } 
		$testGroup->writeGroup($outType);
		return true;
	}
		
	public function getDir()
	{
		return $this->subDirectory;
	}
	
}


?>