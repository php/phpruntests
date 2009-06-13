<?php

class imageReader {
	
	
	private $file = NULL; 
	private $chars = array();
	private $size = 0;
	
	private $pixelGap = 2;

	
	public function __construct($file) {
		
		$this->file = new SplFileInfo($file);
	
		if ($this->file->isFile() == false)
			throw Exception($file.' is not a valid file');	

		$info = getimagesize($this->file);
		
		if ($info[0] != $info[1])
			throw Exception($file.' has invalid dimensions');
			
		$this->size = $info[0];
			
		if ($info['mime'] != 'image/png')
			throw Exception($file.' has to ba a png');
	}

	
	public function read() {

		$img = ImageCreateFromPNG($this->file);

		$x = 0;
		$y = 0;

		for ($i=0; $i<($this->size*$this->size); $i++) {
			
			if ($i>0 && $x%$this->size==0) {
				
				$x = 0;
				$y += $this->pixelGap;
			}
			
			$rgb = ImageColorAt($img, $x, $y);

			if ($rgb) {

				$this->chars[] = (($rgb >> 16) & 0xFF);
				$this->chars[] = (($rgb >> 8) & 0xFF);
				$this->chars[] = ($rgb & 0xFF);
			}
			
			$x += $this->pixelGap;
		}
	}

	
	public function getAsciiChars() {
		
		return $this->chars;
	}
	
	public function setPixelGap($gap) {
		
		$this->pixelGap = is_numeric($gap) ? $gap+1 : 1;
	}

	
}

?>