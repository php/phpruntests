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
	private $results;

	
	public function __construct($runConfiguration, $subDirectory)
	{
		$this->runConfiguration = $runConfiguration;
		$this->subDirectory = $subDirectory;
	}
	
	
	/**
	 * called by the child-process
	 * executes the the test-group
	 */
	public function run()
	{
		$testGroup = new rtPhpTestGroup($this->runConfiguration, $this->subDirectory);
		$testGroup->runGroup($this->runConfiguration);
        $this->results = $testGroup->getResults();
		return true;
	}
	
	
	/**
	 * called by the receiver (parent-process)
	 * writes the results to the OutputWriter
	 * 
	 * @param $cid	the child-id
	 */
	public function evaluate($cid=null)
	{
		$outType = 'list';
        if ($this->runConfiguration->hasCommandLineOption('o')) {           		
        	$outType = $this->runConfiguration->getCommandLineOption('o');
        } 

    	$testOutputWriter = rtTestOutputWriter::getInstance($this->results, $outType);
        $testOutputWriter->write($this->subDirectory, $cid);
	}
	
	
	public function getDir()
	{
		return $this->subDirectory;
	}
	
	
    public function getResults()
    {
    	return $this->results;
    }
    
}


?>