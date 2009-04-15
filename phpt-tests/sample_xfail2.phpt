--TEST--
Try a sample test
--XFAIL--
this should warn
--FILE--
<?php
	echo "Hello world\n";
?>
--EXPECT--
Hello world