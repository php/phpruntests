<?php
/**
 * rtRuntestsConfiguration
 *
 * @category  Testing
 * @package   RUNTESTS
 * @author    Zoe Slattery <zoe@php.net>
 * @author    Stefan Priebsch <spriebsch@php.net>
 * @copyright 2009 The PHP Group
 * @license   http://www.php.net/license/3_01.txt PHP License 3.01
 * @link      http://qa.php.net/
 */

/**
 * Sets up the configuration for the whole test run
 *
 * Settings are derived from command line options and/or environment variables.
 * Runtests also overrides a number of ini settings.
 * 
 *
 * @category  Testing
 * @package   RUNTESTS
 * @author    Zoe Slattery <zoe@php.net>
 * @author    Stefan Priebsch <spriebsch@php.net>
 * @copyright 2009 The PHP Group
 * @license   http://www.php.net/license/3_01.txt PHP License 3.01
 * @link      http://qa.php.net/
 */
abstract class rtRuntestsConfiguration
{
    protected $commandLineArgs;

    private $settings;
    private $setters;
    private $environmentVariables;
    private $commandLine;

    private $settingNames = array (
        'CurrentDirectory' => 'rtCurrentDirectorySetting',
        'WorkingDirectory' => 'rtWorkingDirectorySetting',
        'LogFormat' => 'rtLogFormatSetting',
        'PhpExecutable' => 'rtPhpExecutableSetting',
        'PhpCgiExecutable' => 'rtPhpCgiExecutableSetting',
        'TestFiles' => 'rtTestFileSetting',
        'TestDirectories' => 'rtTestDirectorySetting',
        'PhpCommandLineArguments' => 'rtPhpCommandLineArgSetting',
    );

    protected function init()
    {
        //parse command line
        $this->commandLine = new rtCommandLineOptions;
        $this->commandLine->parse($this->commandLineArgs);

        //get and put envrionment variables
        $this->environmentVariables = rtEnvironmentVariables::getInstance();
        $this->environmentVariables->getUserSuppliedVariables();
    }


    public function configure()
    {
        //extend test command line using TEST_PHP_ARGS
        $options = new rtAddToCommandLine();
        $options->parseAdditionalOptions($this->commandLine, $this->environmentVariables);

        //check configuration preconditions
        $preConditionList = rtPreConditionList::getInstance();
        $preConditionList->check($this->commandLine, $this->environmentVariables);

        //set configuration
        foreach ($this->settingNames as $name => $setting) {
            $this->setters[$name] = new $setting($this);
            $methodName = 'set' . $name;
            $this->$methodName();
        }

    }

    /**
     * Factory: returns rtRuntestsConfiguration subclass for the given os.
     *
     * @returns rtEnvironment
     */
    static public function getInstance ($commandLineArgs, $os = 'Unix')
    {
        if ($os == 'Windows') {
            return new rtWinConfiguration($commandLineArgs);
        } else {
            return new rtUnixConfiguration($commandLineArgs);
        }
    }

    /**
     * Sets the directory that run-tests was started from
     *
     */
    private function setCurrentDirectory()
    {
        $this->settings['CurrentDirectory']= $this->setters['CurrentDirectory']->get();
    }

    /**
     * Sets the directory that run-tests is run from
     *
     */
    private function setWorkingDirectory()
    {
        $this->settings['WorkingDirectory']= $this->setters['WorkingDirectory']->get();
    }

    /**
     * Sets the PHP executable being used to run teh tests
     *
     */
    private function setPhpExecutable()
    {
        $this->settings['PhpExecutable']= $this->setters['PhpExecutable']->get();
    }

    /**
     * Sets the PHP GGI executable being used to run the tests
     *
     */
    private function setPhpCgiExecutable()
    {
        //If the CGI executable hasn't been set using an environmental variable or 'auto', try and derive it from
        //the name of the cli executable.
        //TODO This is *ix specific, need a WIN specific class PhpCgiExecutable setting class
        if($this->setters['PhpCgiExecutable']->get() == null) {
            $this->setters['PhpCgiExecutable']->setFromPhpCli($this->settings['PhpExecutable']);
        }
        $this->settings['PhpCgiExecutable']= $this->setters['PhpCgiExecutable']->get();
    }

    /**
     * Sets the log format
     *
     */
    private function setLogFormat()
    {
        $this->settings['LogFormat']= $this->setters['LogFormat']->get();
    }

    /**
     * Sets the command line arguments for PHP
     *
     */
    private function setPhpCommandLineArguments()
    {
        $this->settings['PhpCommandLineArguments']= $this->setters['PhpCommandLineArguments']->get();
    }

    /**
     * Sets the names of directories to be tested
     *
     */
    private function setTestDirectories()
    {
        $this->settings['TestDirectories'] = $this->setters['TestDirectories']->get();
    }

    /**
     * Sets the names of files to be tested
     *
     * @param array $testFiles
     */
    private function setTestFiles()
    {
        $this->settings['TestFiles'] = $this->setters['TestFiles']->get();
    }

    /**
     * Returns the value of a setting
     *
     * @param string $settingName
     * @param mixed (may be array or string)
     */

    public function getSetting($settingName)
    {
        return $this->settings[$settingName];
    }

    /**
     * Returns the entire settings array
     *
     * @return array $settings
     */
    public function getSettings()
    {
        return $this->settings;
    }

    public function getEnvironmentVariable($name)
    {
        return $this->environmentVariables->getVariable($name);
    }

    public function getEnvironmentVariables()
    {
        return $this->environmentVariables->getVariables();
    }

    public function hasCommandLineOption($option)
    {
        return $this->commandLine->hasOption($option);
    }

    public function getCommandLineOption($option)
    {
        return $this->commandLine->getOption($option);
    }
     
    public function hasEnvironmentVariable($name)
    {
        return $this->environmentVariables->hasVariable($name);
    }

    /**
     * required for testing?
     *
     * @param unknown_type $name
     * @param unknown_type $value
     * @return unknown
     */
    public function setEnvironmentVariable($name, $value)
    {
        $this->environmentVariables->setVariable($name, $value);
    }

    public function getTestFilename()
    {
        return $this->commandLine->getTestFilename();
    }
}
?>
