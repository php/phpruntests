<?php
/*
 * This is experimental code designed to run suites of tests in parallel. The tests within each directory are run in sequence,
 * the diretcories (test-suites?) are run in parallel. The only command line argument is the number of 
 * processes. The rest of the set up is done in the first few lines of code with hard-coded paths.
 * 
 * Note: will only work on Linux. ./confgure --with-pcntl.
 */
require_once("/mnt/workspace/ws_phpscripts/TimingTest/run-tests-parallel.php");
require_once("/mnt/workspace/ws_phpscripts/TimingTest/compare_results.php");
$testdir = "/mnt/workspace/PHP/php53"; //going to run all the tests under thi dir.
$php_executable="/mnt/workspace/PHP/php53/sapi/cli/php";
$run_tests="/mnt/workspace/PHP/php53/run-tests.php";
$out_p = "/tmp/test_p";
$out_s = "/tmp/test_s";

$maxp = $argv[1];
echo "$maxp\n";

putenv("TEST_PHP_EXECUTABLE=$php_executable");

$run = new PhpTestRun($testdir, $php_executable, $run_tests);
$list = $run->get_directory_list();
$nsdir = count($list);
echo "Total subdirectories = $nsdir\n\n";
$test_list=$run->get_test_directory_list();

echo "Number of sub-directories with tests = ".count($test_list)."\n";
$run->set_maxp($maxp);
$f=$run->get_maxp();

echo "Max processes for this run = $f\n";

$start_time = getMicrotime();

$run->run_all_tests_parallel($out_p);

$after_p_time = getMicrotime();

$run->run_all_tests_sequential($out_s);

$after_s_time=getMicrotime();

$ptime = $after_p_time - $start_time;
$stime = $after_s_time - $after_p_time;


CompareResults::compare($test_list, $out_p, $out_s, $testdir);

echo "Time to run tests in parallel = $ptime\nTime to run tests in sequence = $stime\n";

function getMicrotime(){
	list($useg,$seg)=explode(' ',microtime());
	return ((float)$useg+(float)$seg);
}

?>