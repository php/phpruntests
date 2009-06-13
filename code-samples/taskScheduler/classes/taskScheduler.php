<?php

declare(ticks=true);


class taskScheduler
{
	const MSG_QUEUE_KEY = 1234;		// id of the message-queue
	const MSG_QUEUE_SIZE = 1024;	// max-size of a single message
	const KILL_CHILD = 'killBill';	// kill-signal to terminate a child

	private $taskList = array();
	private $processCount = NULL;
	private $inputQueue = NULL;
	private $pidStore = array(); 
	private $time = 0;
	private $countPass = 0;
	private $countFail = 0;
	private $groupTasks = false;
	
	
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

    
    /**
     * sets the task-list which has to be an array of task-objects.
     * it's also possible to use a multidimensional array. in this case the
     * tasks are distributed to the child-processes exactly in the way as they
     * are grouped in the list. the first-level index strictly has to be
     * numeric and continuous starting with zero.
     * 
     * @param array $taskList
     */
	public function setTaskList(array $taskList)
	{
		if (is_array($taskList[0])) {
			$this->groupTasks = true;
			$this->processCount = sizeof($taskList);
		}
		
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
		if ($this->groupTasks !== true && is_numeric($processCount) && $processCount >= 0) {
			$this->processCount = $processCount;
		}
	}

	
	/**
	 * removes the used message-queues.
	 */
    private static function cleanUp()
    {
		@msg_remove_queue(msg_get_queue(self::MSG_QUEUE_KEY));
		@msg_remove_queue(msg_get_queue(self::MSG_QUEUE_KEY+1));
		logg("CLEAN UP"); 	
    }

    
	/**
	 * the signal-handler is called by the interrupt- or quit-signal and calls
	 * the cleanUp-method. 
	 * 
	 * @param int $signal
	 */
	public static function signalHandler($signal)
	{
		logg("SIGNAL: $signal");
		
		switch($signal) {
			
			case SIGINT:
			case SIGQUIT:
				self::cleanUp();
				die("\n");
				break;
				
			default:
				break;
		}
	}

    /**
     * switch to run the classic- or the fork-mode
     */
	public function run()
	{
		if ($this->processCount > 0) {
			$this->runFork();
		}
		else $this->runClassic();
	}
	
	
	/**
	 * executes the tasks in a simple loop 
	 * 
	 * @return void
	 */
	private function runClassic()
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
			
			print ".";
			flush();
			
			$this->taskList[$i] = $task;
		}
		
		$error = microtime(true);
		
		$this->time = round($error-$s,5);

		return;
	}

	
	/**
	 * starts the sender, the receiver and forks the defined
	 * number of child-processes.
	 * 
	 * @return void
	 */
	private function runFork()
	{
		$startTime = microtime(true);
		
		// register signal-handler
		pcntl_signal(SIGINT, "taskScheduler::signalHandler");
		pcntl_signal(SIGQUIT, "taskScheduler::signalHandler");

		// trim the processCount if nesecarry
		if (is_null($this->processCount) || $this->processCount > sizeof($this->taskList)) {
			$this->processCount = sizeof($this->taskList);
		}
				
		// fork the child-processes
		for ($i=0; $i<=$this->processCount; $i++) {

			$this->pidStore[$i] = pcntl_fork();

			switch ($this->pidStore[$i]) {
				
				case -1:	// failure
					die("could not fork"); 
					break;
				
				case 0:		// child
					if ($i==0) {
						$this->sender();
					} else {					
						$cid = ($this->groupTasks == true) ? $i : NULL;
						$this->child($cid);
					}
					break;
					
				default:	// parent
					break;
			}
		}

		// start the receiver
		$this->receiver();

		// wait until all child-processes are terminated
		for ($i=0; $i<=$this->processCount; $i++) {

			pcntl_waitpid($this->pidStore[$i], $status);
			logg("child $i terminated - status $status");
		}

		$endTime = microtime(true);
		$this->time = round($endTime-$startTime,5);
		
		// remove the msg-queue
		self::cleanUp();

		logg("EXIT MAIN");
		return;
	}
	
	
	/**
	 * the receiver is listening to the result-queue and stores the incomming
	 * tasks back to the task-list.
	 * when finished it sends the kill-signal to all children and terminates
	 * itself.
	 * 
	 * @return void
	 */
	private function receiver()
	{
		logg("RECEIVER START - ".sizeof($this->taskList)." tasks");

		$resultQueue = msg_get_queue(self::MSG_QUEUE_KEY+1);

		$task = '';
		$type = 1;
		
		if ($this->groupTasks == true) { 
			$limit = 0;
			foreach ($this->taskList as $list) {
				$limit += sizeof($list);
			} 
		} else {
			$limit = sizeof($this->taskList);  
		}

		for ($i=0; $i<$limit; $i++) {
		
			if (msg_receive($resultQueue, 0, $type, self::MSG_QUEUE_SIZE, $task, true, NULL, $error)) {

				// check state
				if ($task->getState() == task::PASS) {
					$this->countPass++;
				} else {
					$this->countFail++;
				}

				// store result				
				$index = $task->getIndex();
				
				if ($this->groupTasks == true) {
					$this->taskList[$type-2][$index] = $task;
					logg("RECEIVER store task ".($type-1)."-$index");
					
				} else {
					$this->taskList[$index] = $task;
					logg("RECEIVER store task $index");
				}
			}
			else logg("RECEIVER ERROR $error");
		}
		
		$inputQueue = msg_get_queue(self::MSG_QUEUE_KEY);
		
		for ($i=1; $i<=$this->processCount; $i++) {

			if (msg_send($inputQueue, $i, self::KILL_CHILD, true, true, $error)) {
				
				logg("RECEIVER send KILL_CHILD");
			}
			else logg("RECEIVER ERROR $error");
		}

		logg("RECEIVER EXIT");
		return;
	}

	
	/**
	 * the sender is passes through the task-list and distributes the single
	 * tasks to the child-processes using the input-queue.
	 * when finished it terminates itself.
	 * 
	 * @return void
	 */
	private function sender()
	{
		logg("SENDER START - ".sizeof($this->taskList)." tasks");

		$this->inputQueue = msg_get_queue(self::MSG_QUEUE_KEY);

		for ($i=0; $i<sizeof($this->taskList); $i++) {

			if ($this->groupTasks == true) {
				
				for ($j=0; $j<sizeof($this->taskList[$i]); $j++) {

					$this->sendTask($this->taskList[$i][$j], $j, $i+1);
				}

			} else {
				
				$this->sendTask($this->taskList[$i], $i);
			}
		}

		logg("SENDER EXIT");
		exit(0);
	}
	
	
	/**
	 * helper-class of sender.
	 * sends a task to a child-process using the input-queue.
	 * 
	 * @param  task	$task	the task to send
	 * @param  int	$index	the task's index in the taskList 
	 * @param  int	$type	the message-type (default=1)
	 * @return void
	 */
	private function sendTask(task $task, $index, $type=1)
	{
		$task->setIndex($index);

		if (msg_send($this->inputQueue, $type, $task, true, true, $error)) {
			
			logg("SENDER send task $type - $index");
		}
		else logg("SENDER ERROR $error");
		
		return;
	}

	
	/**
	 * the child is listening to the input-queue and executes the incomming
	 * tasks. afterwards it setts the task-state and sends it back to the
	 * receiver by the result-queue.
	 * after receiving the kill-signal from the receiver it terminates itself. 
	 * 
	 * @param  int	$cid	the child-id (default=NULL)
	 * @return void
	 */
	private function child($cid=NULL)
	{
		if (is_null($cid)) {
			$cid = 0;
		}
		
		logg("child $cid START");

		$inputQueue = msg_get_queue(self::MSG_QUEUE_KEY);
		$resultQueue = msg_get_queue(self::MSG_QUEUE_KEY+1);

		$type = 1;

		while (true) {

			if (msg_receive($inputQueue, $cid, $type, self::MSG_QUEUE_SIZE, $task, true, NULL, $error)) {

				if ($task == self::KILL_CHILD)
					break;

				$index = $task->getIndex();
				
				logg("child $cid - run task $index");

				if ($task->run() === true) {			
					$task->setState(task::PASS);
				} else {
					$task->setState(task::FAIL);
				}
				
				print ".";
				flush();

				if (msg_send($resultQueue, $cid+1, $task, true, true, $error)) {
				
					logg("child $cid - send task $index");
				}
				else logg("child $cid ERROR $error");
	
			}
			else logg("child $cid ERROR $error");
		}
		
		logg("child $cid EXIT");
		exit(0);
	}

	
	/**
	 * prints the statistic
	 * 
	 * @return void
	 */
	public function printStatistic()
	{
		print "\n----------------------------------------\n";
		
		if ($this->groupTasks == true) {
		
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
	
}

?>