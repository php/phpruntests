--TEST--
Sample post_raw test
--POST_RAW--
Content-type: multipart/form-data, boundary=AaB03x

--AaB03x
content-disposition: form-data; name="field1"

Hello World
--AaB03x
content-disposition: form-data; name="pics"; filename="file1.txt"
Content-Type: text/plain

abcdef123456789
--AaB03x--
--FILE--
<?php
var_dump($_POST);
var_dump($_FILES);
var_dump(file_get_contents($_FILES["pics"]["tmp_name"]));
?>
--EXPECTF--
array(1) {
  ["field1"]=>
  string(11) "Hello World"
}
array(1) {
  ["pics"]=>
  array(5) {
    ["name"]=>
    string(9) "file1.txt"
    ["type"]=>
    string(10) "text/plain"
    ["tmp_name"]=>
    string(%d) "%s"
    ["error"]=>
    int(0)
    ["size"]=>
    int(15)
  }
}
string(%d) "abcdef123456789"
