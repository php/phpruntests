<?php

  require_once 'PHP/CodeSniffer/Standards/CodingStandard.php';
  
  class PHP_CodeSniffer_Standards_all_allCodingStandard extends PHP_CodeSniffer_Standards_CodingStandard
  {
    public function getIncludedSniffs()
    {
      $result = array();

      foreach (new DirectoryIterator(dirname(__FILE__) . '/..') as $item)
      {
        if (!$item->isDir()) {
          continue;
        }

        if (substr($item->getFilename(), 0, 1) == '.') {
          continue;
        }

        if ($item->getFilename() == 'all') {
          continue;
        }

        require_once $item->getPathname() . '/' . $item->getFilename() . 'CodingStandard.php';

        $classname = 'PHP_CodeSniffer_Standards_' . $item->getFilename() . '_' . $item->getFilename() . 'CodingStandard';
        $std = new $classname;

        $result = array_merge($result, $std->getIncludedSniffs());
      }

      return $result;
    }
  }

?>
