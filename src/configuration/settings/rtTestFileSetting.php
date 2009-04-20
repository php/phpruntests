<?php

/**
 * Class for setting a file or list of PHPT files to be run. These are provided either
 * from the command line or as lists in files using the command line -r or -l options
 *
 */
class rtTestFileSetting extends rtSetting
{
    private $testFiles;

    /**
     * Check each filename - if it's not a directory and the name ends .phpt
     * add it to the list. Also read the -l and -r options and do the same checking with those
     *
     * @param rtCommandLine $commandLine
     *
     */
    public function init(rtRuntestsConfiguration $configuration) {
        $fileArray = $configuration->getTestFilename();

        foreach($fileArray as $fn) {
            if( !is_dir($fn) && substr($fn, -5) == ".phpt") {
                $this->testFiles[] = $fn;
            }
        }

        if($configuration->hasCommandLineOption('l')) {
            $fileArray = file($configuration->getCommandLineOption('l'));
            foreach($fileArray as $file) {
                if(substr(trim($file), -5) == ".phpt") {
                    $this->testFiles[] = trim($file);
                }
            }
        }

        if($configuration->hasCommandLineOption('r')) {
          $fileArray = file($configuration->getCommandLineOption('r'));
          foreach($fileArray as $file) {
            if(substr(trim($file), -5) == ".phpt") {
              $this->testFiles[] = trim($file);
            }
          }
        }
    }

    /**
     * Reurns a list of test files
     *
     */
    public function get() {
        return $this->testFiles;
    }
}
?>
