--TEST--
Sample skipif test
--SKIPIF--
<?php if (!function_exists('blah')) echo "SKIP"; ?>
--FILE--
<?php
  echo "Hello world";
?>
--EXPECTF--
Hello World
