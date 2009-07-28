<?php
/**
 * rtRuntestsConfiguration
 * 
 * Sets up the configuration for the whole test run
 *
 * Settings are derived from command line options and/or environment variables.
 * Runtests also overrides a number of ini settings. 
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
    private $environmentVariables;
    private $commandLine;
    
    private $externalTool = null;

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

    protected function init()
    {
        //parse command line
        $this->commandLine = new rtCommandLineOptions;
        $this->commandLine->parse($this->commandLineArgs);

        //create object to hold environment variables
        $this->environmentVariables = rtEnvironmentVariables::getInstance();       
    }

    /**
     *
     */
    public function configure()
    {
        //extend test command line using TEST_PHP_ARGS
        $options = new rtAddToCommandLine();
        $options->parseAdditionalOptions($this->commandLine, $this->environmentVariables);
        
        //if there is an external tool - configure it
        
        if($this->commandLine->hasOption('m') || $this->commandLine->hasOption('mtool')) {
            $this->externalTool = rtExternalTool::getInstance($this);
            $this->externalTool->checkAvailable($this);
            $this->externalTool->init($this);
            
        }

        //set configuration
        foreach ($this->settingNames as $name => $className) {
            $object = new $className($this);
            $this->settings[$name] = $object->get();
        }
    }

    /**
     * Returns the value of a setting
     *
     * @param string $settingName
     * @param mixed (may be array or string)
     */

    public function getSetting($settingName)
    {
       //echo "$settingName $this->settings[$settingName] \n";
        //var_dump($this->settings);
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
    
    public function getUserEnvironment() {
        $this->environmentVariables->getUserSuppliedVariables();
    }
    
    public function hasExternalTool() {
        if($this->externalTool != null) {
            return true;
        }
        return false;
    }
    
    public function getExternalToolCommand() {
        return $this->externalTool->getCommand();
    }
    
}
?>
