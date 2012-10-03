<?php
/**
 * rtTestConfiguration
 *
 * This class holds configuration settings that are specific to a single test.
 * Test sections which adjust the confiuration for single test include ARGS, ENV, INI
 *
 * @category   Testing
 * @package    RUNTESTS
 * @author     Zoe Slattery <zoe@php.net>
 * @author     Stefan Priebsch <spriebsch@php.net>
 * @copyright  2009 The PHP Group
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link       http://qa.php.net/
 */
class rtTestConfiguration
{
    protected $environmentVariables;
    protected $phpCommandLineArguments;
    protected $phpNonFileSectionCommandLineArguments;
    protected $testCommandLineArguments;
    protected $phpExecutable;
    protected $inputFileString;
    protected $stdin = null;
    protected $cgiTest = false;
    protected $cgiSections = array(
                            'GET',
                            'POST',
                            'POST_RAW',
                            'GZIP_POST',
                            'DEFLATE_POST',
                            'EXPECTHEADERS',
                            'COOKIE',
    );

    public function __construct(rtRuntestsConfiguration $runConfiguration, $sections, $sectionHeadings, $fileSection)
    {
        $this->init($runConfiguration, $sections, $sectionHeadings, $fileSection);
    }

    protected function init(rtRuntestsConfiguration $runConfiguration, $sections, $sectionHeadings, $fileSection)
    {
        $this->setCgiTest($sectionHeadings);

        $this->setEnvironmentVariables($runConfiguration, $sections, $fileSection);
        $this->setPhpCommandLineArguments($runConfiguration, $sections, $fileSection);
        $this->setTestCommandLineArguments($sections);
        $this->setPhpExecutable($runConfiguration, $sectionHeadings);
        $this->setInputFileString($runConfiguration, $sections, $sectionHeadings);
        $this->setStdin($sections, $sectionHeadings);

    }

    protected function setEnvironmentVariables(rtRuntestsConfiguration $runConfiguration, $sections, $fileSection)
    {
        $this->environmentVariables = $runConfiguration->getEnvironmentVariables();

        if (array_key_exists('ENV', $sections)) {
            $this->environmentVariables = array_merge($this->environmentVariables, $sections['ENV']->getTestEnvironmentVariables());
        }

        if($this->cgiTest) {
            $this->environmentVariables['SCRIPT_FILENAME'] = $fileSection->getFileName();
            $this->environmentVariables['PATH_TRANSLATED'] = $fileSection->getFileName();
            //Required by when the cgi has been compiled with force-cgi-redirect.
            $this->environmentVariables['REDIRECT_STATUS'] = '1';
            //Default is GET
            $this->environmentVariables['REQUEST_METHOD'] = 'GET';

            if (array_key_exists('GET', $sections)) {
                $this->environmentVariables = array_merge($this->environmentVariables, $sections['GET']->getGetVariables());
            }
            if (array_key_exists('POST', $sections)) {
                $this->environmentVariables = array_merge($this->environmentVariables, $sections['POST']->getPostVariables());
            }
            if (array_key_exists('GZIP_POST', $sections)) {
                $this->environmentVariables = array_merge($this->environmentVariables, $sections['GZIP_POST']->getPostVariables());
            }
            if (array_key_exists('DEFLATE_POST', $sections)) {
                $this->environmentVariables = array_merge($this->environmentVariables, $sections['DEFLATE_POST']->getPostVariables());
            }
            if (array_key_exists('POST_RAW', $sections)) {
                $this->environmentVariables = array_merge($this->environmentVariables, $sections['POST_RAW']->getPostVariables());
            }
            if (array_key_exists('COOKIE', $sections)) {
                $this->environmentVariables = array_merge($this->environmentVariables, $sections['COOKIE']->getCookieVariables());
            }
        }

    }

    protected function setPhpCommandLineArguments(rtRuntestsConfiguration $runConfiguration, $sections, $fileSection)
    {
        //Use different arguments for running SKIPIF and so on. The INI settings may be designed to 
        //produce an error which will interfere with SKIPIF processing.
        //TODO: But in some cases the INI section is required for SKIPIF so we need another way to deal with this.
    	//$this->phpNonFileSectionCommandLineArguments = $runConfiguration->getSetting('PhpCommandLineArguments');
    	$this->phpCommandLineArguments = $runConfiguration->getSetting('PhpCommandLineArguments');
        if (array_key_exists('INI', $sections)) {
            $sections['INI']->substitutePWD($fileSection->getFileName());
            $additionalArguments = $sections['INI']->getCommandLineArguments();
            $args = new rtIniAsCommandLineArgs();
            $this->phpCommandLineArguments = $args->settingsToArguments($additionalArguments, $this->phpCommandLineArguments);
        }
        //For SKIPIF sections - supress the error reporting which interferes with skip and warn processing
        $this->phpNonFileSectionCommandLineArguments = preg_replace('/error_reporting=32767/', 'error_reporting=0', $this->phpCommandLineArguments);
    }

    protected function setTestCommandLineArguments($sections)
    {
        $this->testCommandLineArguments = '';

        if (array_key_exists('ARGS', $sections)) {
            $this->testCommandLineArguments = $sections['ARGS']->getTestCommandLineArguments();
        }
    }

    protected function setPhpExecutable($runConfiguration, $sectionHeadings)
    {     
        if ($this->cgiTest) {
            if($runConfiguration->getSetting('PhpCgiExecutable') != null) {
                $this->phpExecutable =  $runConfiguration->getSetting('PhpCgiExecutable'). " -C";
            } else {
                $this->phpExecutable = null;
            }
        } else {
            $this->phpExecutable = $runConfiguration->getSetting('PhpExecutable');
        }
    }

    protected function setInputFileString($runConfiguration, $sections, $sectionHeadings)
    {
        $this->inputFileString = '';
        if(in_array('POST', $sectionHeadings)) {
            $this->inputFileString = '< '.$sections['POST']->getPostFileName();
        }
        if(in_array('GZIP_POST', $sectionHeadings)) {
            $this->inputFileString = '< '.$sections['GZIP_POST']->getPostFileName();
        }
        if(in_array('DEFLATE_POST', $sectionHeadings)) {
            $this->inputFileString = '< '.$sections['DEFLATE_POST']->getPostFileName();
        }
        if(in_array('POST_RAW', $sectionHeadings)) {
            $this->inputFileString = '< '.$sections['POST_RAW']->getPostFileName();
        }
    }

    protected function setCgiTest($sectionHeadings)
    {
        $tempArray = array_diff($this->cgiSections, $sectionHeadings);
        if (count($tempArray) < count($this->cgiSections)) {
            $this->cgiTest = true;
        }
    }
    
    protected function setStdin($sections, $sectionHeadings) {
     if(in_array('STDIN', $sectionHeadings)) {
            $this->stdin = $sections['STDIN']->getInputString();
        }
    }

    public function getPhpExecutable()
    {
        return $this->phpExecutable;
    }

    public function getEnvironmentVariables()
    {
        return $this->environmentVariables;
         
    }

    public function getPhpCommandLineArguments()
    {
        return $this->phpCommandLineArguments;
    }
    
    public function getPhpNonFileSectionCommandLineArguments()
    {
        return $this->phpNonFileSectionCommandLineArguments;
    }

    public function getTestCommandLineArguments()
    {
        return $this->testCommandLineArguments;
    }

    public function getInputFileString()
    {
        return $this->inputFileString;
    }
    
    public function getStdin()
    {
        return $this->stdin;
    }

    public function isCgiTest()
    {
        return $this->cgiTest;;
    }
}
?>
