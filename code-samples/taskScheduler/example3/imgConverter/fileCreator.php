<?php

class fileCreator {

	
	private $chars = array();
	private $buffer = '';
	
	
	public function __construct(array $chars) {

		$this->chars = $chars;
	}
	
	
	public function create() {
		
		for ($i=0; $i<sizeof($this->chars); $i++) {
			
			if ($this->chars[$i] != 255) {
			
				$this->buffer.= chr($this->chars[$i]);
			}
		}
	}
	
	
	public function getBufferString() {
		
		return $this->buffer;
	}
	
	
	public function write($file) {

		$f = fopen($file, 'w');

		fwrite($f, $this->buffer);

		fclose($f);
	}
	

	
	
}

?>