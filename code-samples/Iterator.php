<?php

  function getTests($aDirectory)
  {
    $result = array();

    foreach (new PhptFilterIterator(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($aDirectory))) as $item)
    {
      $result[] = $item->getPathname();
    }

    return $result;
  }

  class PhptFilterIterator extends FilterIterator
  {
    public function accept()
    {
      return (substr($this->current(), -strlen('.phpt')) == '.phpt');
    }
  }

?>
