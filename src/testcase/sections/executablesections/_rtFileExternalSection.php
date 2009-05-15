<?php
/**
 * rtFileExternalSection
 * Executes the code in the --FILE_EXTERNAL-- section
 *
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
class rtFileExternalSection extends rtExecutableSection
{
    private $twoBlankLines = '\r?\n\r?\n';
    
    public function setExecutableFileName($testName)
    {
        $this->fileName = $testName.".php";
    }

    public function run(rtPhpTest $testCase, rtRuntestsConfiguration $runConfiguration)
    {
        $this->status = array();
        $this->writeExecutableFile();

        $this->copyExternalFileContent();

        $phpExecutable = $testCase->testConfiguration->getPhpExecutable();

        // The CGI excutable is null if it is not available, check and SKIP if necessary
        if ($phpExecutable != null) {
            $phpCommand = $phpExecutable;
            $phpCommand .= ' '. $testCase->testConfiguration->getPhpCommandLineArguments();
            $phpCommand .= ' -f '.$this->fileName;
            $phpCommand .= ' '.$testCase->testConfiguration->getTestCommandLineArguments();
            $phpCommand .= ' 2>&1 '.$testCase->testConfiguration->getInputFileString();
             

            $PhpRunner = new rtPhpRunner($phpCommand,
            $testCase->testConfiguration->getEnvironmentVariables(),
            $runConfiguration->getSetting('WorkingDirectory')
            );

            try {
                $this->output = $PhpRunner->runphp();
                
                //If it's a CGI test sort the headers out here
                if(substr($phpExecutable, -2) == '-C') {
                    
                    if (preg_match("/^(.*?)$this->twoBlankLines(.*)/s", $this->output, $match)) {
                        $this->output = $match[2];
                        $this->headers = $match[1];
                    }
                     
                }


            } catch (rtPhpRunnerException $e) {
                $this->status['fail'] = $e->getMessage();
            }
        } else {
            $this->status['skip'] = 'The CGI executable is unavailable';
        }

        return $this->status;
    }
    
    
    public function getHeaders()
    {
        return $this->headers;
    }
    
    
    private function copyExternalFileContent()
    {
    	if (sizeof($this->sectionContents) == 1) {
    	
	    	$c = $this->sectionContents[0];
	        
	    	// don't allow tests to retrieve files from anywhere but this subdirectory
	        $file = dirname($this->fileName).'/'.trim(str_replace('..', '', $c));
	        
	        if (file_exists($file)) {
	        
	            $content = file_get_contents($file);         
	            file_put_contents($this->fileName, $content);
	            return true;
	        
	        } else {
	        	
	        	$this->status['fail'] = 'Can not open external file '.$file;
	        }
        
    	} else {
    		
    		$this->status['fail'] = 'One file per testcase permitted.';
    	}
        
        return false;
    }
}
?>