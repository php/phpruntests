<?php
/*
 * Main php file for run-tests.php
 */
require_once dirname(__FILE__) . '/rtAutoload.php';



$phpTestRun = new rtPhpTestRun($argv);
$phpTestRun->run();


?>
