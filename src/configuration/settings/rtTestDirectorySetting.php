<?php
/**
 * rtTestDirectorySetting
 *
 * Class for setting the test directory (or directories) - these 
 * contain PHPT files to be tested and can be provided on 
 * the command line or by  TEST_PHP_USER
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
class rtTestDirectorySetting extends rtSetting
{
    protected $testDir = null;
    
    /**
     * Check each option - if it's a directory add it to the list.
     * If not leave it.
     *
     * @param rtCommandLine $commandLine
     * @param rtEnvironmentVariables $environmentVariables
     */
    public function init(rtRuntestsConfiguration $configuration)
    {
        $fileArray = $configuration->getTestFilename();
        
        //phar does not understand relative paths, so if we have just given a relative path from the
        //currrent working directory phar will not find the file. Here, if the file does not exist 
        //but a file with cwd prepended does, we reset the name with the cwd prepend.
        for ($i=0; $i<count($fileArray); $i++) {
        	if(!file_exists($fileArray[$i])) {
        		if(file_exists($configuration->getSetting('CurrentDirectory') . '/' . $fileArray[$i]))
        		$fileArray[$i] = $configuration->getSetting('CurrentDirectory') . '/' . $fileArray[$i];
        	}
        }
        

        foreach ($fileArray as $file) {
            if (is_dir($file)) {
                $this->testDir[]= $file;
            }
        }

        if ($configuration->hasEnvironmentVariable('TEST_PHP_USER')) {
            $fileArray = trim($configuration->getEnvironmentVariable('TEST_PHP_USER'));
            foreach ($fileArray as $file) {
                if (is_dir($file)) {
                    $this->testDir[]= $file;
                }
            }
        }
    }

    /**
     * Set the test directory/directories list in the configuration
     * 
     */
    public function get()
    {
        return $this->testDir;
    }  
}
?>
