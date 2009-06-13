<?php

class taskSleep extends task implements taskInterface
{
	private $sleep = 0;
	
	
	public function __construct($sleep)
	{
		$this->sleep = $sleep;
	}
	
	
	public function run()
	{
		sleep($this->sleep);
		return true;
	}
}


?>