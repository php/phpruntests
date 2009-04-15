<?php


class rtPhptFilterIterator extends FilterIterator
  {
    public function accept()
    {
      return (substr($this->current(), -strlen('.phpt')) == '.phpt');
    }
  }
?>