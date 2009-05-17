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

    public function run(rtPhpTest $testCase, rtRuntestsConfiguration $runConfiguration)
    {
    	if ($this->copyExternalFileContent() === true) {

            return parent::run($testCase, $runConfiguration);
        }

        return $this->status;
    }

    
    private function copyExternalFileContent()
    {
    	if (sizeof($this->sectionContents) == 1) {
    	
	    	$file = $this->sectionContents[0];
	        
	    	// don't allow tests to retrieve files from anywhere but this subdirectory
	        $file = dirname($this->fileName).'/'.trim(str_replace('..', '', $file));
	        
	        if (file_exists($file)) {
	        
	            $this->sectionContents[0] = file_get_contents($file);            
	            return true;
	        
	        } else {
	        	
	        	$this->status['fail'] = 'Can not open external file '.$file;
	        }
        
    	} else {
    		
    		$this->status['fail'] = 'One file per testcase permitted.';
    	}
        
        return false;
    }
    
    public function writeExecutableFile() {
        file_put_contents($this->fileName,  (binary) $this->sectionContents[0]);
    }
}
?>
