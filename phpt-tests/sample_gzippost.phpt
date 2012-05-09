--TEST--
Sample test for GZIP_POST
--GZIP_POST--
blahblah=blah
--FILE--
<?php
$content = file_get_contents('php://input');
$string = gzdecode($content);
if ($string != "blahblah=blah") 
{
  echo "invalid gzipped content";
} else {
  echo "It worked!\n";
}

?>
--EXPECT--
It worked!

