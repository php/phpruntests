--TEST--
Sample test for DEFLATE_POST
--DEFLATE_POST--
blahblah=blah
--FILE--
<?php
$content = file_get_contents('php://input');

if (gzuncompress($content) != 'blahblah=blah') {
  echo "invalid gzipped content";
} else {
  echo "It worked!\n";
}
?>
--EXPECT--
It worked!

