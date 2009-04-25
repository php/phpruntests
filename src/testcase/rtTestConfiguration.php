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
    private $cgiSections = array(
                            'GET',
                            'POST',
                            'POST_RAW',
                            'GZIP_POST',
                            'DEFLATE_POST',
                            'EXPECTHEADERS',
                            'COOKIE',
    );

    public function __construct(rtRuntestsConfiguration $runConfiguration, $sections, $sectionHeadings)
    {
        $this->init($runConfiguration, $sections, $sectionHeadings);
    }

    private function init(rtRuntestsConfiguration $runConfiguration, $sections, $sectionHeadings)
    {
        $this->setEnvironmentVariables($runConfiguration, $sections);
        $this->setPhpCommandLineArguments($runConfiguration, $sections);
        $this->setTestCommandLineArguments($sections);
        $this->setPhpExecutable($runConfiguration, $sectionHeadings);
    }

    private function setEnvironmentVariables(rtRuntestsConfiguration $runConfiguration, $sections)
    {
        $this->environmentVariables = $runConfiguration->getEnvironmentVariables();
        if (array_key_exists('ENV', $sections)) {
            $this->environmentVariables = array_merge($this->environmentVariables, $sections['ENV']->getTestEnvironmentVariables());
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
        $tempArray = array_diff($this->cgiSections, $sectionHeadings);
        if (count($tempArray) < count($this->cgiSections)) {
            $this->phpExecutable =  $runConfiguration->getSetting('PhpCgiExecutable');
        } else {
            $this->phpExecutable = $runConfiguration->getSetting('PhpExecutable');
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
}
?>
