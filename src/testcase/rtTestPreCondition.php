<?php


interface rtTestPreCondition {
  
  public function isMet(array $testContents);
  
  public function getMessage(); 
}

?>
