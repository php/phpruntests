--TEST--
Try a sample test
--FILE--
<?php
	echo "Hello world\n";
?>
--EXPECTREGEX--
Hello w\w{3}d