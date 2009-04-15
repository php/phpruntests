--TEST--
sample clean
--FILE--
<?php

  $file_name = dirname(__FILE__) . "/cleantest.tmp";
  file_put_contents($file_name, "hello world");
  echo  "written\n";
  
?>
--CLEAN--
<?php
  $file_name = dirname(__FILE__) . "/cleantest.tmp";
  unlink($file_name);
 ?>
--EXPECT--
written