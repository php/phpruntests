<?php

class rtException extends RuntimeException
{
	public function __toString()
	{
		$r = "\n--------------------------------------------------------------------------------\n";
		$r .= "RUN-TESTS EXCEPTION\n";
		$r .= $this->getMessage()." (CODE ".$this->getCode().")\n";
		$r .= $this->getFile().":".$this->getLine()."\n\n";
		$r .= $this->getTraceAsString();
		$r .= "\n--------------------------------------------------------------------------------\n";
		
		return $r;
	}
}

?>