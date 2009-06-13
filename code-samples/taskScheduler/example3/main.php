<?php

include 'taskConvert.php';

include 'imgConverter/fileCreator.php';
include 'imgConverter/fileReader.php';
include 'imgConverter/imageCreator.php';
include 'imgConverter/imageReader.php';


function createTaskList()
{
	return readSource('example3/files');
}


function readSource($base, $path=NULL)
{
	$files = array();
	
	foreach (new DirectoryIterator($base.'/src/'.$path) as $file) {
		
		if ($file->isDot()) continue;

		$name = $file->getFileName();
		
		if (substr($name,0,1) == '.') continue;
		
		if ($name == 'CVS') continue;

		if ($file->isDir()) {
			
			$dest = $base.'/dest'.$path.'/'.$name;

			if (!file_exists($dest)) {
			
				mkdir($dest);
				chmod($dest, 0777);
			}
							
			$files[] = readSource($base, $path.'/'.$name);
		}
		
		elseif ($file->isFile()) {

			$files[] = new taskConvert($path.'/'.$name);
		}
	}

	return $files;
}

?>