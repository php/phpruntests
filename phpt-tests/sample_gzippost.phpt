--TEST--
Sample test for GZIP_POST
--POST--
blahblah=blah
--GZIP_POST--
1
--FILE--
<?php
$content = file_get_contents('php://input');

if ($content != gzencode('blahblah=blah')) 
{
  echo "invalid gzipped content";
} else {
  echo "It worked!\n";
}

?>
--EXPECT--
It worked!

