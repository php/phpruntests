<?php
/**
 * rtFileExternalSection
 * Executes the code in the --FILE_EXTERNAL-- section
 *
 * @category  Testing
 * @package   RUNTESTS
 * @author    Zoe Slattery <zoe@php.net>
 * @author    Stefan Priebsch <spriebsch@php.net>
 * @author    Georg Gradwohl <g2@php.net>
 * @copyright 2009 The PHP Group
 * @license   http://www.php.net/license/3_01.txt PHP License 3.01
 * @link      http://qa.php.net/
 */
class rtFileExternalSection extends rtFileSection
{
	/**
	 * @param  rtPhpTest                $testCase
	 * @param  rtRuntestsConfiguration  $runConfiguration
	 * @return Array                    $status
	 */
    public function run(rtPhpTest $testCase, rtRuntestsConfiguration $runConfiguration)
    {
        $testStatus = $this->copyExternalFileContent($testCase->getStatus());
    	if ($testStatus->getValue('fail') == false) {
            return parent::run($testCase, $runConfiguration);
        }
        return $testStatus;
    }

    /**
     * @return boolean
     */
    protected function copyExternalFileContent($testStatus)
    {
    	if (sizeof($this->sectionContents) != 1) {
    	    $testStatus->setTrue('fail');
            $testStatus->setMessage('fail', 'One file per testcase permitted.');
            return $testStatus;
    	}
    	
        $file = $this->sectionContents[0];
	        
	    // don't allow tests to retrieve files from anywhere but this subdirectory
        $file = dirname($this->fileName).'/'.trim(str_replace('..', '', $file));
	        
        if (!file_exists($file)) {
            $testStatus->setTrue('fail');
            $testStatus->setMessage('fail', 'Can not open external file '.$file );
            return $testStatus;
        }
	        
        $this->sectionContents[0] = file_get_contents($file);            
        return $testStatus;
    }
    
    public function writeExecutableFile() {
        file_put_contents($this->fileName,  (binary) $this->sectionContents[0]);
    }
}
?>
