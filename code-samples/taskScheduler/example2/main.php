<?php

include 'taskSleep.php';


function createTaskList()
{
	$list = array();

	for ($i=0; $i<10; $i++) {

		$list[$i] = new taskSleep($i%3);
	}
	
	return $list;
} 

?>