<?php

class taskConvert extends task implements taskInterface
{
	private $sourceFile = NULL;
	
	
	public function __construct($sourceFile)
	{
		$this->sourceFile = $sourceFile;
	}
	
	
	public function run()
	{
		if (is_null($this->sourceFile)) {
			
			$this->setState(self::FAIL);
			$this->setMessage('no source file');
			return false;
		}

		$reader = new fileReader('example3/files/src'.$this->sourceFile);
		$reader->read();
		$chars = $reader->getAsciiChars();
		
		$n = 'example3/files/dest'.$this->sourceFile.'.png';

		$img = new imageCreator($chars);
		$img->draw();
		$img->saveImage($n);

		return true;
	}
	
}


?>