--TEST--
Try a sample test with DONE
--FILE--
<?php
	echo "Hello world\n";
?>
===DONE===
<?php
   echo "If you get this line the test has failed \n";
?>
--EXPECT--
Hello world
===DONE===
