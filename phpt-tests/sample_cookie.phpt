--TEST--
Sample COOKIE test
--COOKIE--
hello=World;goodbye=MrChips
--FILE--
<?php
var_dump($_COOKIE);

?>
--EXPECT--
array(2) {
  ["hello"]=>
  string(5) "World"
  ["goodbye"]=>
  string(7) "MrChips"
}
