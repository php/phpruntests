<?php
/**
 * rtTaskScheduler
 *
 * Main class of the TaskScheduler
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
class rtTaskScheduler
{
	protected $taskList = array();	 // the list of the tasks to be executed
	protected $resultList = array(); // list of results
	protected $processCount = 0;	 // the number of processes
	protected $reportStatus = 0;	 // the level of repoerting as tests atr run (0, 1, 2)
	
	

	
	/**
	 * constructor
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
    
    
    public static function getInstance()
    {
    	if (extension_loaded('pcntl')) {
    		return new rtTaskSchedulerFile();
    	}
    	
    	return new rtTaskScheduler();
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
	 * @return array $resultList
	 */
	public function getResultList()
	{
		return $this->resultList;
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
	 * @return integer $processCount
	 */
	public function getProcessCount()
	{
		return $this->processCount;
	}

		
    /**
     * -1: no output
     *  0: dots
     *  1: basic
     *  2: only not-pased
     *  3: everything 
     * 
     * @param numeric $reportStatus
     */
	public function setReportStatus($reportStatus)
	{
		if (is_numeric($reportStatus)) {	
			$this->reportStatus = $reportStatus;
		}
	}
	

	/**
	 * executes the tasks in a simple loop 
	 * 
	 * @return void
	 */
	public function run()
	{

		for ($i=0; $i<sizeof($this->taskList); $i++) {
			
			$task = $this->taskList[$i];
			$task->run();
			$results = $task->getResult();

			rtTestOutputWriter::flushResult($results, $this->reportStatus);
			$this->resultList[] = $results;
		}

		return;
	}
}

?>