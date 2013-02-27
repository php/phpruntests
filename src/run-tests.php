<?php
/*
 * Main php file for run-tests.php
 */


error_reporting(E_ALL);

/**
 * rtExceptionHandler
 * 
 * @param Exception $e
 * @return unknown_type
 */
function rtExceptionHandler(Exception $e) {
	
	print $e->__toString();
}

set_exception_handler('rtExceptionHandler');


/*
 * check the version of the running php-executable and
 * ensure that is 5.3 or higher
 */
if (version_compare(PHP_VERSION, '5.3.0RC1', '<')) {
    die('This version of run-tests requires PHP 5.3RC1 or higher, you are running ' . PHP_VERSION . "\n");
}

require_once dirname(__FILE__) . '/rtAutoload.php';

$s = microtime(true);

$phpTestRun = new rtPhpTestRun($argv);
$phpTestRun->run();

$e = microtime(true);

print "\n".($e-$s)." sec\n\n";

?>
