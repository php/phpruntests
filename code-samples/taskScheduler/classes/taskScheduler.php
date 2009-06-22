<?php

class taskScheduler
{
	protected $taskList = array();	// the list of the tasks to be executed
	protected $processCount = 0;	// the number of processes

	protected $time = 0;			// the needed time
	protected $countPass = 0;		// counts the passed tasks
	protected $countFail = 0;		// counts the failed tasks
	
	protected $memStore = array();	// stores the mem-usage after an incomming task


	
	/**
	 * the constructor
	 * 
	 * @param array $taskList		(optional)
	 * @param int	$processCount	(optional)
	 */
    public function __construct(array $taskList=NULL, $processCount=NULL)
	{
		if (is_array($taskList)) {
			$this->setTaskList($taskList);
		}
		
		$this->setProcessCount($processCount);
    }
    
    
    public static function getInstance(array $taskList=NULL, $processCount=NUL, $useMsgQ=false)
    {
    	if (extension_loaded('pcntl')) {

    		if ($useMsgQ === true) {
    		
    			return new taskSchedulerMsgQ($taskList, $processCount);
    		}

    		return new taskSchedulerFile($taskList, $processCount);
    	}
    	
    	return new taskScheduler($taskList, $processCount);
    }

    
    /**
     * sets the task-list which has to be an array of task-objects.
	 *
     * @param array $taskList
     */
	public function setTaskList(array $taskList)
	{
		$this->taskList = $taskList;
	}


	/**
	 * @return array $taskList
	 */
	public function getTaskList()
	{
		return $this->taskList;
	}
	
	
	/**
	 * sets the number of child-processes.
	 * in the case of using a multidimensional task-list this parameter is
	 * ignored and set to the number of task-groups.
	 *  
	 * @param int $count
	 */
	public function setProcessCount($processCount)
	{
		if (is_numeric($processCount) && $processCount >= 0) {
			$this->processCount = $processCount;
		}
	}

	
	/**
	 * executes the tasks in a simple loop 
	 * 
	 * @return void
	 */
	public function run()
	{
		$s = microtime(true);
		
		for ($i=0; $i<sizeof($this->taskList); $i++) {
			
			$task = $this->taskList[$i];
			
			if ($task->run() === true) {			
				$task->setState(task::PASS);
				$this->countPass++;
			} else {
				$task->setState(task::FAIL);
				$this->countFail++;
			}
			
			$this->memStore[] = memory_get_usage(true);
			
			print ".";
			flush();
			
			$this->taskList[$i] = $task;
		}
		
		$error = microtime(true);
		
		$this->time = round($error-$s,5);

		return;
	}

	
	/**
	 * prints the statistic
	 * 
	 * @return void
	 */
	public function printStatistic()
	{
		print "\n----------------------------------------\n";
		
		if (is_array($this->taskList[0])) {
		
			$count = 0;
			foreach ($this->taskList as $list) {
				$count += sizeof($list);
			}
			
			print "Groups:\t\t".sizeof($this->taskList)."\n";
			print "Tasks:\t\t".$count."\n";

		} else {
			
			$count = sizeof($this->taskList);
			print "Tasks:\t\t".$count."\n";
		}

		print "PASSED:\t\t".$this->countPass." (".round($this->countPass/$count*100,2)."%)\n";
		print "FAILED:\t\t".$this->countFail." (".round($this->countFail/$count*100,2)."%)\n";
		print "Processes:\t".$this->processCount."\n";
		print "Seconds:\t".$this->time."\n";
		
		if ($this->processCount > 0) {
			print "AVG sec/task:\t".round($this->time/$this->processCount,5)."\n";
			print "Memory-MAX:\t".number_format(max($this->memStore))."\n";
			print "Memory-MIN:\t".number_format(min($this->memStore))."\n";
			$avg = array_sum($this->memStore)/sizeof($this->memStore);
			print "Memory-AVG:\t".number_format($avg)."\n";
		}

		print "----------------------------------------\n";
		flush();
	}
	
	
	/**
	 * prints a overview of the faild tasks
	 * 
	 * @return void
	 */
	public function printFailedTasks()
	{
		if ($this->countFail > 0) {
		
			print "FAILED TASKS";
			print "\n----------------------------------------\n";
			
			for ($i=0; $i<sizeof($this->taskList); $i++) {
	
				$task = $this->taskList[$i];
				
				if ($task->getState() == task::FAIL) {
					print "Task $i: ".$task->getMessage()."\n";
				}
			}
			
			print "----------------------------------------\n";
			flush();
		}
	}
	
	
	
	public function printMemStatistic($int=10)
	{
		print "MEMORY-USAGE";
		print "\n----------------------------------------\n";
		
		$int = ceil(sizeof($this->memStore)/$int);
		
		$title = "TASK:\t";
		$body = "kB:\t";
		
		for ($i=0; $i<sizeof($this->memStore); $i+=$int) {
			
			$title .= "$i\t";
			$body .= round($this->memStore[$i]/1000)."\t";
		}
		
		print $title."\n".$body;
		
		print "\n----------------------------------------\n";
		flush();		
	}
	
}

?>