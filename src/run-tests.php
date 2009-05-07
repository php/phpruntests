<?php
/*
 * Main php file for run-tests.php
 */


/*
 * check the version of the running php-executable and
 * ensure that is 5.3 or higher
 */
$v = phpversion();

$major = substr($v, 0, 1);
$minor = substr($v, 2, 1);

$isVersionOk = false;

if ($major > 5) {
    $isVersionOk = true;

} elseif ($major == 5) {

    if ($minor >= 3) {
        $isVersionOk = true;
    }
}


if ($isVersionOk) {

    require_once dirname(__FILE__) . '/rtAutoload.php';

    $phpTestRun = new rtPhpTestRun($argv);
    $phpTestRun->run();

} else {
	
    die("This version of run-tests requires PHP 5.3 or higher.\nYou can check your current version by executing 'php -v' from the command line.\n");
}

?>
