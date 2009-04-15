--TEST--some random characters
Sample extra chars
--SKIPIF--
<?php if (!function_exists('blah')) echo "SKIP"; ?>
--FILE-- more random characters
<?php
  echo "Hello world";
?>
--EXPECTF--still more
Hello World