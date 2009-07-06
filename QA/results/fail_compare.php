<?php

//Compare FAILED tests from run-tests and phpruntests
//php fail_compare.php phprt_ext rt_ext

$top_level = $argv[3];

$mytests = file($argv[1]);
$myfails = array();

foreach($mytests as $test) {
	if (preg_match("/($top_level\/\S+)\s{1},(.*)\s{1}FAIL\s{1}/", $test, $matches)) {
                if(!preg_match("/XFAIL/", $matches[2])) {
                	$myfails[] = $matches[1] . ".phpt";
                }
	}
} 


$rttests = file($argv[2]);
$rtfails = array();

foreach($rttests as $test) {
	if (preg_match("/FAIL.*\[(.+\.phpt)\]/", $test, $matches)) {
		//echo $matches[1] . "\n";
                $rtfails[] = $matches[1];
	}
} 

$fail_only_my = array_diff($myfails, $rtfails);
$nmy = count($fail_only_my);

echo "\n =====> $nmy tests fail in phpruntests and do not fail in run-tests\n";

foreach($fail_only_my as $fail) {
	echo "$fail \n";
}

$fail_only_rt = array_diff($rtfails, $myfails);
$nrt = count ($fail_only_rt);

echo "\n =====> $nrt tests fail in run-tests and do not fail in phpruntests\n";

foreach($fail_only_rt as $fail) {
	echo "$fail\n";
}
?>
