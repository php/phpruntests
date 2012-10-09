<?php

$srcRoot = $argv[1];
$targetRoot = $argv[2]; 

$targetname = $targetRoot . "/run-tests.phar";

if(file_exists($targetname)) {
    unlink($targetname);
}
$phar = new Phar($targetname, FilesystemIterator::CURRENT_AS_FILEINFO| FilesystemIterator::KEY_AS_FILENAME, "run-tests.phar");
$phar->setStub($phar->createDefaultStub("run-tests.php"));
$phar->buildFromDirectory($srcRoot,'/\.php$|\.txt$/');
?>
