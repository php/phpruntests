<?php

include 'taskFileWriter.php';


function createTaskList($lim=NULL)
{
	if (is_null($lim)) $lim = 5;
	
	$list = array();

	for ($j=0; $j<$lim; $j++) {
		
		$list[$j] = array();
	
		$subLim = $lim*($j+1);
		
		for ($i=0; $i<$subLim; $i++) {
	
			$d = (($i+$j)%($lim*2));
			$s = 0;
			
			if ($d == 0) {
				$s = 1;
				$d++;
			}
			
			$d *= $lim;
			
			$list[$j][$i] = new taskFileWriter($j.$i, $d, $s);
		}
	}
	
	return $list;
} 

?>