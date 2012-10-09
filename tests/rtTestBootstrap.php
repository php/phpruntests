<?php

require_once __DIR__ . '/../src/rtAutoload.php';

/**
 * Check to see if the PHP and CGI executables are in a config file
 */
if(file_exists(__DIR__ . '/../build/phpdefinitions.txt')) {
	$phpdefs=file(__DIR__ . '/../build/phpdefinitions.txt');
	foreach($phpdefs as $line) {
		if(preg_match('/^php_to_test=(.*)/', $line, $matches)) {
         define('RT_PHP_PATH', trim($matches[1]));
		}
		if(preg_match('/^php_cgi_to_test=(.*)/', $line, $matches)) {		
    		define('RT_PHP_CGI_PATH', trim($matches[1]));
		}
		if(preg_match('/^zlib=(.*)/', $line, $matches)) {		
    		define('ZLIB', trim($matches[1]));
		}
	}
} else {
	echo "You must provide PHP versions in build/phpdefinitions.txt\n";
}
?>
