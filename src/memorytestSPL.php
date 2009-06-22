<?php

include 'rtUtil.php';

$topDir = $argv[1];


$s1 = getMicroTime();

$phptDirectoryList= rtUtil::getDirectoryList($topDir);

$s2 =  getMicroTime();
$t0 = $s2 - $s1;
echo "Time to create using SPL = " . $t0 . "\n";

echo "Memory : " . memory_get_usage() . " bytes \n";

var_dump(count($phptDirectoryList));


function getMicrotime(){
	list($useg,$seg)=explode(' ',microtime());
	return ((float)$useg+(float)$seg);
}

?>
