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
    private $environmentVariables;
    private $phpCommandLineArguments;
    private $testCommandLineArguments;
    private $phpExecutable;
    private $inputFileString;
    private $cgiTest = false;
    private $cgiSections = array(
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

    private function init(rtRuntestsConfiguration $runConfiguration, $sections, $sectionHeadings, $fileSection)
    {
        $this->isCgiTest($sectionHeadings);

        $this->setEnvironmentVariables($runConfiguration, $sections, $fileSection);
        $this->setPhpCommandLineArguments($runConfiguration, $sections);
        $this->setTestCommandLineArguments($sections);
        $this->setPhpExecutable($runConfiguration, $sectionHeadings);
        $this->setInputFileString($runConfiguration, $sections, $sectionHeadings);

        if($this->cgiTest) {
            $this->environmentVariables['SCRIPT_FILENAME'] = $fileSection->getFileName();
            $this->environmentVariables['PATH_TRANSLATED'] = $fileSection->getFileName();
            //Required by when the cgi has been compiled with force-cgi-redirect.
            $this->environmentVariables['REDIRECT_STATUS'] = '1';
        }
    }

    private function setEnvironmentVariables(rtRuntestsConfiguration $runConfiguration, $sections, $fileSection)
    {
        $this->environmentVariables = $runConfiguration->getEnvironmentVariables();

        if (array_key_exists('ENV', $sections)) {
            $this->environmentVariables = array_merge($this->environmentVariables, $sections['ENV']->getTestEnvironmentVariables());
        }
        if (array_key_exists('GET', $sections)) {
            $this->environmentVariables = array_merge($this->environmentVariables, $sections['GET']->getGetVariables());
        }
       if (array_key_exists('POST', $sections)) {
            $this->environmentVariables = array_merge($this->environmentVariables, $sections['POST']->getPostVariables());
        }
       if (array_key_exists('GZIP_POST', $sections)) {
            $this->environmentVariables = array_merge($this->environmentVariables, $sections['GZIP_POST']->getPostVariables());
        }

    }

    private function setPhpCommandLineArguments(rtRuntestsConfiguration $runConfiguration, $sections)
    {
        $this->phpCommandLineArguments = $runConfiguration->getSetting('PhpCommandLineArguments');
        if (array_key_exists('INI', $sections)) {
            $additionalArguments = $sections['INI']->getCommandLineArguments();
            $args = new rtIniAsCommandLineArgs();
            $this->phpCommandLineArguments = $args->settingsToArguments($additionalArguments, $this->phpCommandLineArguments);
        }
    }

    private function setTestCommandLineArguments($sections)
    {
        $this->testCommandLineArguments = '';

        if (array_key_exists('ARGS', $sections)) {
            $this->testCommandLineArguments = $sections['ARGS']->getTestCommandLineArguments();
        }
    }

    private function setPhpExecutable($runConfiguration, $sectionHeadings)
    {
        if ($this->cgiTest) {
            $this->phpExecutable =  $runConfiguration->getSetting('PhpCgiExecutable'). " -C";
        } else {
            $this->phpExecutable = $runConfiguration->getSetting('PhpExecutable');
        }
    }

    private function setInputFileString($runConfiguration, $sections, $sectionHeadings)
    {
        $this->inputFileString = '';
        if(in_array('POST', $sectionHeadings)) {
            $this->inputFileString = '< '.$sections['POST']->getPostFileName();
        }
        if(in_array('GZIP_POST', $sectionHeadings)) {
            $this->inputFileString = '< '.$sections['GZIP_POST']->getPostFileName();
        }
    }

    private function isCgiTest($sectionHeadings)
    {
        $tempArray = array_diff($this->cgiSections, $sectionHeadings);
        if (count($tempArray) < count($this->cgiSections)) {
            $this->cgiTest = true;
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

    public function getTestCommandLineArguments()
    {
        return $this->testCommandLineArguments;
    }

    public function getInputFileString()
    {
        return $this->inputFileString;
    }
}
?>
