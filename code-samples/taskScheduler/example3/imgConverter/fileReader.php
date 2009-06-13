<?php

class fileReader {
	
	
	private $file = NULL; 
	private $chars = array();
	
	private $trimFile = false;
	
	
	public function __construct($file) {
		
		$this->file = new SplFileInfo($file);
	
		if ($this->file->isFile() == false)
			die($file.' is not a valid file');		
	}
	
	
	public function getAsciiChars() {
		
		return $this->chars;
	}
	
	
	public function setTrim($trim) {
		
		$this->trimFile = is_bool($trim) ? $trim : false;
	}
	
	
	public function read() {

		$lines = file($this->file->getPathname(), FILE_IGNORE_NEW_LINES);

		foreach ($lines as $line) {
			
			if ($this->trimFile) $line = trim($line);
			
			for ($i=0; $i<strlen($line); $i++) {
			
				$this->chars[] = ord($line{$i});		
			}
			
			$this->chars[] = 10; // new line			
		}
	}
	

	
}

?>