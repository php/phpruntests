--TEST--
Sample expect headers test
--FILE--
<?php
header("HTTP/1.0 404 Not Found");
echo 'hello world';
?>
--EXPECTHEADERS--
Status: 404 Not Found
Content-type: text/html

--EXPECT--
hello world




