<?php
/**
 * rtDirectoryList
 *
 * Lists all of the directories under a top level directory.
 * This is currently not used for anything
 * 
 * @category   Testing
 * @package    RUNTESTS
 * @author     Zoe Slattery <zoe@php.net>
 * @author     Stefan Priebsch <spriebsch@php.net>
 * @copyright  2009 The PHP Group
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
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
