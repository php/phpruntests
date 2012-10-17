<?php

require_once dirname(__FILE__) . '/../src/rtAutoload.php';

class rtTaskSchedulerTest extends PHPUnit_Framework_TestCase
{
	public function testResult()
    {
    	/* TODO REWRITE!
    	// Need to rewrite this to test the PHP group runner
    	$taskList = array();
    	$expected = array();
    	$results = array();
    	
    	// create 10 tasks with random numbers
    	for ($i=0; $i<10; $i++) {
    		$n = rand(0,9);	
    		$expected[$i] = $n+1;
    		$taskList[$i] = new rtTaskIncTest($n);
    	}
    	
    	// run the task-scheduler
		$scheduler = rtTaskScheduler::getInstance();
		$scheduler->setTaskList($taskList);
		$scheduler->setProcessCount(3);
		$scheduler->setReportStatus(-1);
		$scheduler->run();
		
		
		$actual=array();
		
		foreach($scheduler->getResultList() as $result) {
			$array = $result->getTestStatusList();
			$actual[] = $array[0];
		}
		
		sort($actual);
		sort($expected);
		

		$this->assertEquals($expected, $actual);
		*/
    }
}


/**
 * rtTaskIncTest
 * 
 * nested helper-class for rtTaskSchedulerTest
 */
class rtTaskIncTest extends rtTask implements rtTaskInterface
{
	protected $num = null;
	
	
	public function __construct($num)
	{
		$this->num = $num;
	}
	
	public function run()
	{
		$this->result = new rtTaskResult($this->num + 1);
		
		return true;
	}
	
}
class rtTaskResult
{
	private $status = array();
	
	public function __construct($num) {
		$this->status[] = $num;
	}
	
	public function getTestStatusList() {
		return $this->status;
	}
}

?>