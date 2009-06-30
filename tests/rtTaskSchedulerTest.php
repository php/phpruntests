<?php

require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__) . '/../src/rtAutoload.php';

class rtTaskSchedulerTest extends PHPUnit_Framework_TestCase
{
	public function testResult()
    {
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
		$scheduler->run();
		
		// get the results from the manupilated task-list
		foreach ($scheduler->getTaskList() as $task) {
			$results[] = $task->getNumber();
		}
		
		$this->assertEquals($expected, $results);
    }
}


/**
 * rtTaskIncTest
 * 
 * nested helper-class for rtTaskSchedulerTest
 */
class rtTaskIncTest extends rtTask implements rtTaskInterface
{
	private $num = null;
	
	public function __construct($num)
	{
		$this->num = $num;
	}
	
	public function run()
	{
		$this->num++;
		return true;
	}
	
	public function getNumber()
	{
		return $this->num;
	}
	
	// temp - remove this function
	public function getDir() {}
}

?>