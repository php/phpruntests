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

    /*
     * Returns the index associated with the minimum value in an array
     *
     */
    public static function getMin($a) {
        $x = array_keys($a, min($a));
        return $x[0];
    }
    /*
     * Strip any part of the path name before one of the recognised levels
     * TODO What happens when just running tests? This is only used to match
     * up with a weighting table. Assume no weight will be matched.
     * Would it be better to have some other way to check that it's being run from
     * the root of the PHP source? Do this in rtCommandLineOptions anyway(?)
     */
    public static function stripPath($t) {
        $topLevelDirectory = array("Zend", "sapi", "ext", "tests");
        foreach($topLevelDirectory as $tld) {
            if(preg_match("/\W{1}$tld\W{1}/", $t, $matches, PREG_OFFSET_CAPTURE)) {
                $offset = $matches[0][1] + 1;
                return substr($t, $offset);
            }
        }
        return "";
    }

    /*
     * Standard code to read a configuration file and return results as a key->value array
     */
    public static function readConfigurationFile($fileName) {

        $a = array();
        $fc = file($fileName);

        foreach($fc as $line) {
            if(substr($line, 0, 1) != '#') {
                list($key, $value) = explode(':',trim($line));
                $a[$key] = $value;
            }
        }
        return $a;
    }
}
?>
