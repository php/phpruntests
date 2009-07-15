--TEST--
Try a sample test
--GET--
--POST--
   --COOKIE--
--FILE--
<?php
	echo "Hello world\n";
?>
--EXPECT--
Hello world
