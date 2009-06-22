<?php
/**
 * rtUtil
 *
 * Static utility methods
 *
 * @category  Testing
 * @package   RUNTESTS
 * @author    Zoe Slattery <zoe@php.net>
 * @author    Stefan Priebsch <spriebsch@php.net>
 * @copyright 2009 The PHP Group
 * @license   http://www.php.net/license/3_01.txt PHP License 3.01
 * @link      http://qa.php.net/
 */
class rtUtil
{
    public static function getTestList($aDirectory)
    {
        $result = array();
        $result = glob($aDirectory. "/*.phpt");
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
        $subDirectories = array();
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($aDirectory)) as $directory) {
            $subDirectories[] = $directory->getPath() . "/";
        }

        $subDirectoriesUnique = array_unique($subDirectories);

        $phptDirectories = array();

        foreach ($subDirectoriesUnique as $subDir) {
            if(count(self::getTestList($subDir)) > 0) {
                $phptDirectories[] = $subDir;
            }
        }

        return $phptDirectories;
    }


	/**
	 * returns a list of directories containing a phpt-file
	 *
     * @param $path
     * @return array
	 */
	public static function parseDir($path)
	{
		$list = array();
		$found = false;

		foreach (scandir($path) as $file) { 
	
			if (substr($file, 0, 1) != '.' && $file != 'CVS') {
	
				if (is_dir($path.'/'.$file)) {
	
					$list = array_merge($list, rtUtil::parseDir($path.'/'.$file));

				} elseif ($found === false && strpos($file, '.phpt') !== false) {
						
					$list[] = $path.'/';
					$found = true;
				}
			} 
		}
		
		return $list;
	}
    
	
    /**
     * This is the original version of getDirectoryList which uses PhptFilterIterator
     */
    public static function getTestListOld($aDirectory)
    {
        $result = array();

        foreach (new rtPhptFilterIterator(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($aDirectory))) as $item) {
            $result[] = $item->getPathname();
        }

        return $result;
    }

    /**
     * This is the original version of getDirectoryList which uses PhptFilterIterator
     */
    public static function getDirectoryListOld($aDirectory)
    {
        $result = array();

        foreach (new rtPhptFilterIterator(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($aDirectory))) as $directory) {
            $result[] = $directory->getPath() . "/";
        }

        return array_unique($result);
    }

}
?>
