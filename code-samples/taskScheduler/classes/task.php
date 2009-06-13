<?php

abstract class task
{
	const NOEX = 0;
	const PASS = 1;
	const FAIL = -1;

	private $state = self::NOEX;
	private $index = NULL;
	private $message = NULL;
	
	
	public function setState($state)
	{
		$this->state = $state;		
	}
	
	public function getState()
	{
		return $this->state;
	}
	
	
	public function setMessage($msg)
	{
		$this->message = $msg;
	}

	public function getMessage()
	{
		return $this->message;
	}

	
	public function setIndex($index)
	{
		$this->index = $index;
	}

	public function getIndex()
	{
		return $this->index;
	}
	
}

?>