<?php

//Compare the output from new run-tests with the old version
//Takes two files and a keyword as input
//Usage:
//php compareNewOld.php new_output old_output keyword
//
//where new_output is an output file generated using -o csv.
//      old_output is the standard output fro run-tests.php
//      key is the name of the top_level directory of the tests (eg QA, unless you move them anywhere else)

//Search for Warning, Notice, Fatal

$warnCount = 0;
$noticeCount = 0;
$fatalCount = 0;

$top_level = $argv[3];

$rttests = file($argv[2]);
$mytests = file($argv[1]);

foreach($mytests as $test) {
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

$myfails = parseNew($mytests, 'FAIL', $top_level);
$myborks = parseNew($mytests, 'BORK', $top_level);
$myskips = parseNew($mytests, 'SKIP', $top_level);


$rtfails = parseOld($rttests, 'FAIL');
$rtborks = parseOld($rttests, 'BORK');
$rtskips = parseOld($rttests, 'SKIP');

echo "\n\nIn the new code (phpruntests)...\n";
echo "     Number of Warnings: $warnCount\n     Number of Notices: $noticeCount\n     Number of Fatal: $fatalCount\n";

compareAndPrint($myfails, $rtfails, 'fail');
compareAndPrint($myborks, $rtborks, 'bork');
compareAndPrint($myskips, $rtskips, 'skip');

function parseNew($output, $searchFor, $top_level) {

       	 $result = array();
	foreach($output as $line) {
		if (preg_match("/$top_level\/((ext|sapi|Zend|tests)\/\S+)\s{1},(.*)\s{1}$searchFor\s{1}/", $line, $matches)) {
       		         if($searchFor == 'FAIL') {
                                 if(!preg_match("/XFAIL/", $line)) {
       		         		$result[] = $matches[1];
				}
       		         } else {
       		         	$result[] = $matches[1];
                         }
		}

	}
return $result;
}

function parseOld($output, $searchFor) {
	$result = array();
	foreach($output as $line) {
		if (preg_match("/^TEST\s+\d+\/\d+\s+\[(.+\.phpt)\]\s+$searchFor\s+/", $line, $matches)) {
       	         	$result[] = $matches[1];
		}
	}
return $result;
}
function compareAndPrint($my, $rt, $type) {
	$only_my = array_diff($my, $rt);
	$nmy = count($only_my);

	echo "\n =====> $nmy tests $type in phpruntests and do not $type in run-tests\n";

	foreach($only_my as $line) {
		echo "$line \n";
	}

	$only_rt = array_diff($rt, $my);
	$nrt = count ($only_rt);

	echo "\n =====> $nrt tests $type in run-tests and do not $type in phpruntests\n";

	foreach($only_rt as $line) {
		echo "$line\n";
	}
}
?>
