<?php
class rtUtil {

  public static function getTestList($aDirectory)
  {
    $result = array();

    foreach (new rtPhptFilterIterator(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($aDirectory))) as $item)
    {
      $result[] = $item->getPathname();
    }

    return $result;
  }

  /**
   * Returns a list of subdirectories (including the current directory) if they contatin .phpt files
   * Directory names are full paths and are not terminated with a /
   * There should be a cleaner way to do this
   *
   * @param path $aDirectory
   * @return array
   */
  public static function getDirectoryList($aDirectory)
  {
    $result = array();

   foreach (new rtPhptFilterIterator(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($aDirectory))) as $directory)
    {
      $result[] = $directory->getPath()."/";
    }

    return array_unique($result);
  }

}
?>