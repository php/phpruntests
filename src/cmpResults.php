<?php

error_reporting(E_ALL);


function domToArray($dom) {

	$list = array();
	$tests = $dom->getElementsByTagName('testcase');

	foreach ($tests as $test) {
		
		$n = $test->getElementsByTagName('name');
		$n = $n->item(0)->nodeValue;
		
		$s = $test->getElementsByTagName('status');
		$s = $s->item(0)->nodeValue;
		
		$list[$n] = $s;
	}
	
	return $list;
}


function getResultList() {

	$list = array();
	
	foreach (scandir('.') as $file) { 
	
		if (strpos($file, '.xml') !== false && strpos($file, 'results_') !== false) {
		
			$xml = new DOMDocument();
			$xml->load($file);
			$n = explode('_', basename($file, ".xml"));
			$list[$n[1]] = domToArray($xml);
		} 
	}
	
	return $list;
}


function compareWith($list, $index) {
	
	$cmp = false;
	$base = $list[$index];
	
	foreach ($list as $key => $result) {
		
		if ($key == $index) {
			$cmp = true;
		}
		
		elseif ($cmp === true) {
			
			print "\nDIFF $index - $key\n";
			
			$dif = array_diff_assoc($result, $base);
			
			if (sizeof($dif) > 0) {
				print_r($dif);
			} else {
				print 'NONE';
			}
			
			print "\n";
		}	
	}
}



$results = getResultList();

if (sizeof($results) < 2) {
	die("at least 2 results needed\n");
}

foreach ($results as $index => $list) {
	
	compareWith($results, $index);
}






?>