<?php

include '../src/rtUtil.php';


// arguments

$argc = sizeof($argv);

if ($argc == 2 || $argc == 3) {
	
	$dir = $argv[1];
	$simple = isset($argv[2]) ? true : false;
	
} else {
	
	die("USAGE\n");
}


// execute

if ($simple) {
	
	print "rtUtil::parseDir\n";
	$s = microtime(true);
	$list = rtUtil::parseDir($dir);
	$e = microtime(true);
		
} else {
	
	print "rtUtil::getDirectoryList\n";
	$s = microtime(true);
	$list = rtUtil::getDirectoryList($dir);
	$e = microtime(true);	
}

print "list:\t".sizeof($list)."\n";
print "time:\t".round($e-$s, 5)."\n";
print "mem:\t".memory_get_usage()."\n";

?>

