<?php
/**
 * rtTestFileSetting
 *
 * Class for setting a file or list of PHPT files to be run. 
 * These are provided either from the command line or as 
 * lists in files using the command line -r or -l options
 * 
 * @category   Testing
 * @package    RUNTESTS
 * @author     Zoe Slattery <zoe@php.net>
 * @author     Stefan Priebsch <spriebsch@php.net>
 * @copyright  2009 The PHP Group
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link       http://qa.php.net/
 * 
 */
class rtTestFileSetting extends rtSetting
{
    protected $testFiles;

    /**
     * Check each filename - if it's not a directory and the name ends .phpt
     * add it to the list. Also read the -l and -r options and do the same checking with those
     *
     * @param rtCommandLine $commandLine
     *
     */
    public function init(rtRuntestsConfiguration $configuration)
    {
        $fileArray = $configuration->getTestFilename();
        
        //phar does not understand relative paths, so if we have just given a relative path from the
        //currrent working directory phar will not find the file. Here, if the file does not exist 
        //but a file with cwd prepended does, we reset the name with the cwd prepend.
        //TODO: If this only applies to phar is there a better way? 
        
    	for ($i=0; $i<count($fileArray); $i++) {
        	if(!file_exists($fileArray[$i])) {
        		if(file_exists($configuration->getSetting('CurrentDirectory') . '/' . $fileArray[$i]))
        		$fileArray[$i] = $configuration->getSetting('CurrentDirectory') . '/' . $fileArray[$i];
        	}
        }

        foreach ($fileArray as $fn) {
            if (!is_dir($fn) && substr($fn, -5) == ".phpt") {
                $this->testFiles[] = $fn;
            }
        }

        if ($configuration->hasCommandLineOption('l')) {
            $fileArray = file($configuration->getCommandLineOption('l'));
            foreach ($fileArray as $file) {
                if (substr(trim($file), -5) == ".phpt") {
                    $this->testFiles[] = trim($file);
                }
            }
        }

        if ($configuration->hasCommandLineOption('r')) {
            $fileArray = file($configuration->getCommandLineOption('r'));
            foreach ($fileArray as $file) {
                if (substr(trim($file), -5) == ".phpt") {
                    $this->testFiles[] = trim($file);
                }
            }
        }
    }

    /**
     * Reurns a list of test files
     *
     */
    public function get()
    {
        return $this->testFiles;
    }
}
?>
