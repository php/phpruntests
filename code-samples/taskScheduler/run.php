<?php

include 'classes/taskScheduler.php';
include 'classes/taskSchedulerMsgQ.php';
include 'classes/taskSchedulerFile.php';
include 'classes/taskInterface.php';
include 'classes/task.php';


// logger

date_default_timezone_set("Europe/Berlin");

function logg($msg) {

	$debug = false;
	
	if ($debug) {
		
		print  '['.date('h:i:s').'] '.$msg."\n";
		flush();
	}
}


// arguments

$argc = sizeof($argv);

if ($argc >= 2 || $argc <= 4) {
	
	$src = $argv[1];
	$count = isset($argv[2]) ? $argv[2] : NULL;
	$useMsgQ = isset($argv[3]);
	
} else {
	
	die("USAGE: php run.php example processCount useMsgQ\n");
}


// include exmaple & create task-list

$src = $src.'/main.php';

if (!file_exists($src)) {
	
	die("invalid example\n");
}

include $src;

$taskList = createTaskList($count);



// init scheduler

$c = taskScheduler::getInstance($taskList, $count, $useMsgQ);

$c->setTaskList($taskList);
$c->setProcessCount($count);
$c->run();

$c->printStatistic();
$c->printFailedTasks();
$c->printMemStatistic(10);

// var_dump($c->getTaskList());

exit(0);

?>

