--TEST--
Try a GET section
--GET--
hello=World&goodbye=MrChips
--FILE--
<?php
var_dump($_GET);
?>
--EXPECT--
array(2) {
  ["hello"]=>
  string(5) "World"
  ["goodbye"]=>
  string(7) "MrChips"
}
