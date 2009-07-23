<?php

require_once __DIR__ . '/../src/rtAutoload.php';

/**
 * Define the Path to the PHP executable
 */
if (!defined('RT_PHP_PATH')) {
  define('RT_PHP_PATH', trim(shell_exec("which php")));
}

/**
 * Define the Path to the PHP CGI executable
 */
if (!defined('RT_PHP_CGI_PATH')) {
  define('RT_PHP_CGI_PATH', trim(shell_exec("which php-cgi")));
}

?>
