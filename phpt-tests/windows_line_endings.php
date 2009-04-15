<?php
// Utility to create a test case with windows line endings.
$testFile = $argv[1];
$windowsTestFile = $argv[2];

$string = file_get_contents($testFile);
$string2 = preg_replace("/\\n/", "\r\n", $string);
file_put_contents($windowsTestFile, (binary) $string2);

?>
