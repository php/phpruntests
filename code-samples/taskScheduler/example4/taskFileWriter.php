<?php

class taskFileWriter extends task implements taskInterface
{
	private $id = NULL;
	private $durations = 1;
	private $sleep = 0;
	
	private $txt = "Lorem ipsum dolor sit amet, consectetur adipisici elit, sed eiusmod tempor incidunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquid ex ea commodi consequat. Quis aute iure reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint obcaecat cupiditat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";
	
	
	public function __construct($id, $durations, $sleep)
	{
		$this->id = $id;
		$this->durations = $durations;
		$this->sleep = $sleep;
	}
	
	
	public function run()
	{
		$name = 'example4/files/tmp_'.$this->id.'.txt';
		$size = 0;
		
		for ($i=0; $i<=$this->durations; $i++) {
			
			$size += file_put_contents($name, $this->txt, FILE_APPEND | LOCK_EX);
		}

		file_get_contents($name);

		$x = exp(asin(atan2($size, microtime(true))));
		
		sleep($this->sleep);

		$y = sqrt(acosh(atan2($x, microtime(true))));
		
		if ($x == $y) {
			
			$this->setMessage('impossible...');
			return false;
		}
		
		unlink($name);
		
		return true;
	}
}


?>