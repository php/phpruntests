<?php
/*
 * Main php file for run-tests.php
 */


/*
 * check the version of the running php-executable and
 * ensure that is 5.3 or higher
 */
if (version_compare(PHP_VERSION, '5.3.0RC1', '<')) {
    die('This version of run-tests requires PHP 5.3RC1 or higher, you are running ' . PHP_VERSION . "\n");
}

require_once dirname(__FILE__) . '/rtAutoload.php';

$phpTestRun = new rtPhpTestRun($argv);
$phpTestRun->run();

?>
