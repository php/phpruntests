--TEST--
Sample POST test
--POST--
hello=World&goodbye=MrChips
--FILE--
<?php
var_dump($_POST);

?>
--EXPECT--
array(2) {
  ["hello"]=>
  string(5) "World"
  ["goodbye"]=>
  string(7) "MrChips"
}
