--TEST--
Sample expect headers test
--FILE--
<?php
header("HTTP/1.0 404 Not Found");
echo 'hello world';
?>
--EXPECTHEADERS--
Status: 404 Not Found
X-Powered-By: PHP/5.3.0RC2-dev
Content-type: text/html

--EXPECT--
hello world




