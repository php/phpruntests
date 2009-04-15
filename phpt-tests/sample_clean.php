<?php

  $file_name = dirname(__FILE__) . "/cleantest.tmp";
  file_put_contents($file_name, "hello world");
  echo  "written\n";
  
?>
