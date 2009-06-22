<?php

include 'rtUtil.php';

$topDir = $argv[1];


$s3 = getMicroTime();
$phptDirectoryList2= rtUtil::parseDir($topDir);
$s4 = getMicroTime();
$t1 = $s4 - $s3;

echo "Time to create using scandir = " . $t1 . "\n";
echo "Memory : " . memory_get_usage() . " bytes \n";

var_dump(count($phptDirectoryList2));


function getMicrotime(){
	list($useg,$seg)=explode(' ',microtime());
	return ((float)$useg+(float)$seg);
}

?>
