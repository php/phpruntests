<?php
/**
 * BuildClassMap
 *
 * This is stand alone code to build the file rtClassMap used by rtAutoload to find classes.
 * It assumes that all classes have the same name at the file name (less .php)  and all are prefixed by 'rt'
 *
 * @category   Testing
 * @package    RUNTESTS
 * @author     Zoe Slattery <zoe@php.net>
 * @author     Stefan Priebsch <spriebsch@php.net>
 * @copyright  2009 The PHP Group
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 *
 */

$map = new BuildClassMap();
$map->buildMap();

class BuildClassMap
{
    public function buildMap()
    {
        $thisDir = getcwd();   

        $sourceFiles = $this->getSourceList($thisDir);

        $mapString = '<?php' . "\n";
        $mapString .= '/**' . "\n";
        $mapString .= ' * Autoload Class Map' . "\n";
        $mapString .= ' *' . "\n";
        $mapString .= ' * This is a generated file. Do not edit!' . "\n";
        $mapString .= ' *' . "\n";
        $mapString .= ' * To re-generate this file, run the script ' . basename(__FILE__) . '.' . "\n";
        $mapString .= ' */' . "\n";

        $mapString .= '$rtClassMap = array('."\n";

        sort($sourceFiles);

        foreach ($sourceFiles as $class) {
            $relativeLocation = substr($class, strlen($thisDir.'/'));
          
            $className = basename($class, '.php');
          
            $spaces = $this->getSpaces(strlen($className));
          
            $mapString .= "    ".'\''.$className.'\''. $spaces. " => ".'\''.$relativeLocation.'\','."\n";
        }

        $mapString .= ');'."\n";
        $mapString .= '?>'."\n";

        file_put_contents($thisDir.'/rtClassMap.php', $mapString);
    }

    public function getSpaces($length)
    {
        $spaces = "";
        $nspaces = 40 - $length;

        for ($i=0; $i < $nspaces; $i++) {
            $spaces .=" ";
        }

        return $spaces;
    }

    public function getSourceList($aDirectory)
    {
        $files = array();

        foreach (new PhpFilterIterator(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($aDirectory))) as $item) {
            $files[] = $item->getPathname();
        }

        return $files;
    }
}

class PhpFilterIterator extends FilterIterator
{
    public function accept()
    {
        if (substr($this->current(), -strlen('.php')) == '.php') {
            if (substr(basename($this->current()), 0, strlen('rt')) == 'rt') {
                return true;
            }
        }

        return false;
    }
}
?>
