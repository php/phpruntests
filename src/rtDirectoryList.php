<?php

/**
 * Lists all of the directories under a top level directory.
 * This is currently not used for anything
 *
 */
class rtDirectoryList
{
  /**
   * Finds a list of subdirectories under the top level ditectory and returns the full path names in an array
   *
   * @param string $topDirectory
   * @return array
   */
  public function getSubDirectoryPaths($topDirectory)
  {
      $result = array($topDirectory);
      
      foreach (new RecursiveIteratorIterator(new ParentIterator(new RecursiveDirectoryIterator($topDirectory)), 1) as $dir) {
          $result[] = $dir->getPathName();
      }
 
      return $result;
  }
}
?>
