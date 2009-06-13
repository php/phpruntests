<?php

include 'taskCalculate.php';


function createTaskList()
{
	$list = array();

	for ($i=0; $i<rand(128,256); $i++) {
		
		$num = array();
		
		for ($j=0; $j<rand(32,64); $j++) {
			
			$num[$j] = rand(0,9);
		}

		$list[$i] = new taskCalculate($num);
	}
	
	return $list;
} 

?>