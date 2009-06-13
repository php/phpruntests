<?php

class taskCalculate extends task implements taskInterface
{
	private $numbers = array();
	private $result = 0;
	
	
	public function __construct(array $numbers)
	{
		$this->numbers = $numbers;
	}
	
	
	public function run()
	{
		$r = 0;
		
		foreach($this->numbers as $num) {

			$num = (int)$num;
			$r += $num;
		}
				
		if ($r%26 == 0) {
			
			$this->setMessage("just an example");
			return false;
		}

		$this->result = $r;
		return true;
	}
	
	
	public function getResult()
	{
		return $this->result;
	}
	
}


?>