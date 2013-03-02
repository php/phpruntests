<?php
/**
 * rtTaskSchedulerFile
 *
 * extention of TaskScheduler, implements a ipc via temporary files
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
class rtTaskSchedulerFile extends rtTaskScheduler
{
	const TMP_FILE = 'taskFile';
	const LOG_FILE = '/tmp/parallellog.csv';
	
	protected $pidStore = array(); 	// stores the pids of all child-processes
	protected $groupTasks = false;	// are the tasks already grouped by target processor

    
	/**
	 * the signal-handler is called by the interrupt- or quit-signal. this is
	 * necessary to cleanup the tmp files and terminate the script correct.
	 * 
	 * @param int $signal
	 */
	public static function signalHandler($signal)
	{
		exit(0);
	}
	
    
    /**
     * sets the task-list which has to be an array of task-objects.
     * it's also possible to use a multidimensional array. in this case the
     * tasks are distributed to the child-processes exactly in the way as they
     * are grouped in the list. the first-level index strictly has to be
     * numeric and continuous starting with zero.
     * 
     * @param array $taskList
     * @Overrides
     */
	public function setTaskList(array $taskList)
	{ 
		//Pre-allocate tasks to processors
		
		if (isset($taskList[0]) && is_array($taskList[0])) {
			$this->groupTasks = true;
			$this->processCount = sizeof($taskList);
		}
		
		$this->taskList = $taskList;
	}

	/**
	 * sets the number of child-processes.
	 * in the case of using a multidimensional task-list this parameter is
	 * ignored and set to the number of task-groups.
	 *  
	 * @param int $count
	 * @Overrides
	 */
	public function setProcessCount($processCount)
	{
		if ($this->groupTasks !== true && is_numeric($processCount) && $processCount >= 0) {
			$this->processCount = $processCount;
		}
	}

	
	/**
	 * starts the sender, the receiver and forks the defined
	 * number of child-processes.
	 * 
	 * @return void
	 * @Overrides
	 */
	public function run()
	{
	    //clears the logfile	    
	    //file_put_contents(self::LOG_FILE, "");
	    
		if ($this->processCount == 0) {
			return parent::run();
		}

		$startTime = microtime(true);

		// trim the processCount if necessary
		if ($this->processCount > sizeof($this->taskList)) {
			$this->processCount = sizeof($this->taskList);
		}

		
		// distribute the task to the children
		$this->distributeTasks();

		// fork the child-processes
		for ($i=0; $i<$this->processCount; $i++) {

			$this->pidStore[$i] = pcntl_fork();

			switch ($this->pidStore[$i]) {
				
				case -1:	// failure
					die("could not fork"); 
					break;
				
				case 0:		// child
					$this->child($i);
					break;
					
				default:	// parent
					break;
			}
		}
		
		// register signal-handler
		pcntl_signal(SIGINT, "rtTaskSchedulerFile::signalHandler");
		pcntl_signal(SIGQUIT, "rtTaskSchedulerFile::signalHandler");

		
		// wait until all child-processes are terminated
		for ($i=0; $i<$this->processCount; $i++) {
			pcntl_waitpid($this->pidStore[$i], $status);
		}
		
		// ensure that the tmp-files are completely written
		sleep(1);
		
		// collecting the results
		$this->receiver();

		$endTime = microtime(true);
		$this->time = round($endTime-$startTime,5);

		return;
	}

	
	/**
	 * creates a temporary file for each child which stores serialized task-objects
	 * 
	 */
	protected function distributeTasks() {

		if ($this->groupTasks == true) { 

			foreach ($this->taskList as $cid => $list) {				
				for ($i=0; $i<sizeof($list); $i++) {
					$str = serialize($list[$i])."[END]";
					file_put_contents(self::TMP_FILE.$cid, $str, FILE_APPEND);
				}
			}
			 
		} else {

			for ($i=0; $i<sizeof($this->taskList); $i++) {
				$cid = $i%$this->processCount;
				$str = serialize($this->taskList[$i])."[END]";
				file_put_contents(self::TMP_FILE.$cid, $str, FILE_APPEND);
			}
		}
	}

	
	/**
	 * 
	 * 
	 * @return void
	 */
	protected function receiver()
	{
		for ($cid=0; $cid<$this->processCount; $cid++) {

			$response = file_get_contents(self::TMP_FILE.$cid);
			$response = explode("[END]", $response);
			array_pop($response);

			foreach ($response as $testGroupResult) {
				
				$testGroupResult = unserialize($testGroupResult);
				
				if ($testGroupResult === false) {
					print "ERROR unserialize - receiver $cid\n";
					continue;
				}

				$this->resultList[] = $testGroupResult;
			}

			unlink(self::TMP_FILE.$cid);
		}
	}

	
	/**
	 * executes the assigned tasks and stores the serialized task-object in
	 * the task-file. 
	 * 
	 * @param  int	$cid	the child-id
	 * @return void
	 */
	protected function child($cid)
	{
		$taskList = file_get_contents(self::TMP_FILE.$cid);
		$taskList = explode('[END]', $taskList);
		array_pop($taskList);

		file_put_contents(self::TMP_FILE.$cid, '');
        $count = 0;
		foreach ($taskList as $task) {
			$s = microtime(true);
			
			$task = unserialize($task);
			
			if ($task === false) {
				print "ERROR unserialize - cid $cid\n";
				continue;
			}
			
			$task->run();
			
			$e = microtime(true);
			
			//$t = round($e - $s, 2);
			
			//$logstring = $task->getSubDirectory() . ", " .$cid . ", " .   $s . ", " . $e . ", " . $t . "\n";
			
			//file_put_contents(self::LOG_FILE, $logstring, FILE_APPEND);
						
			$taskResult = $task->getResult();
		
			//StatusList is an array 'testname=>statusObject'
			$statusList = $taskResult->getTestStatusList();
			$taskResult->setProc($cid);
			$taskResult->setCount($count);
			
			/*
			if (isset($statusList[0]) && is_object($statusList[0])) {
				$statusList[0]->setTime($e-$s);
			}
            */
			
			rtTestOutputWriter::flushResult($statusList, $this->reportStatus, $cid);
			
			$response = serialize($taskResult)."[END]";
			file_put_contents(self::TMP_FILE.$cid, $response, FILE_APPEND);
			
			$count++;
		}

		exit(0);
	}

	
}

?>