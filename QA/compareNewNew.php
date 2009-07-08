<?php

//Compare the output from new run-tests with the old version
//Takes two files and a keyword as input
//Usage:
//php compareNewOld.php new_output1 new_output2 keyword
//
//where new_output1 is an output file generated using -o csv.
//      new_output2  is an output from  generated using -o csv
//      key is the name of the top_level directory of the tests (eg QA, unless you move them anywhere else)

//Search for Warning, Notice, Fatal

$warnCount = 0;
$noticeCount = 0;
$fatalCount = 0;

$top_level = $argv[3];

$tests2 = file($argv[2]);
$tests1 = file($argv[1]);

echo "\nCheck errors in $argv[1]\n";
checkNotices($tests1);

echo "\nCheck errors in $argv[2]\n";
checkNotices($tests2);


$fails1 = parseNew($tests1, 'FAIL', $top_level);
$borks1 = parseNew($tests1, 'BORK', $top_level);
$skips1 = parseNew($tests1, 'SKIP', $top_level);

$fails2 = parseNew($tests2, 'FAIL', $top_level);
$borks2 = parseNew($tests2, 'BORK', $top_level);
$skips2 = parseNew($tests2, 'SKIP', $top_level);


compareAndPrint($fails1, $fails2, 'fail');
compareAndPrint($borks1, $borks2, 'bork');
compareAndPrint($skips1, $skips2, 'skip');

function parseNew($output, $searchFor, $top_level) {

       	 $result = array();
	foreach($output as $line) {
		if (preg_match("/$top_level\/((ext|sapi|Zend|tests)\/\S+)\s{1},(.*)\s{1}$searchFor\s{1}/", $line, $matches)) {
       		         if($searchFor == 'FAIL') {
                                 if(!preg_match("/XFAIL/", $matches[2])) {
       		         		$result[] = $matches[1] . ".phpt";
				}
       		         } else {
       		         	$result[] = $matches[1] . ".phpt";
                         }
		}

	} 
return $result;
}

function compareAndPrint($my, $rt, $type) {
	$only_my = array_diff($my, $rt);
	$nmy = count($only_my);

	echo "\n =====> $nmy tests $type in file 1 and do not $type in file 2\n";

	foreach($only_my as $line) {
		echo "$line \n";
	}

	$only_rt = array_diff($rt, $my);
	$nrt = count ($only_rt);

	echo "\n =====> $nrt tests $type in file2 and do not $type in file1\n";

	foreach($only_rt as $line) {
		echo "$line\n";
	}
}
function checkNotices($tests) {
        $warnCount = 0;
        $noticeCount = 0;
        $fatalCount = 0;

	foreach($tests as $test) {
       		 if(preg_match("/Warning/", $test)) {
			$warnCount++;
       		 }

       		 if(preg_match("/Notice/", $test)) {
			$noticeCount++;
       		 }

       		 if(preg_match("/Fatal/", $test)) {
			$fatalCount++;
       		 }

	} 
	echo "Number of Warnings: $warnCount     Number of Notices: $noticeCount     Number of Fatal: $fatalCount\n";
}
?>
