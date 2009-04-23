--TEST--
Sample test for DEFLATE_POST
--POST--
blahblah=blah
--DEFLATE_POST--
1
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

