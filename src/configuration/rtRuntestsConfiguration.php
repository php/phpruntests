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

    protected $settings;
    protected $environmentVariables;
    protected $commandLine;
    protected $operatingSystem;    
    protected $memoryTool = null;
    protected $taskWeightings = array();
    const WEIGHT_FILE = "/data/task_weight_file.csv";
    

    protected $settingNames = array (
    
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
        $this->environmentVariables = rtEnvironmentVariables::getInstance($this->operatingSystem);
        
        if($this->commandLine->hasOption('z')) {
            $this->setTaskWeightings();
        }
    }

    /**
     *
     */
    public function configure()
    {
        //extend test command line using TEST_PHP_ARGS
        $options = new rtAddToCommandLine();
        $options->parseAdditionalOptions($this->commandLine, $this->environmentVariables);
        
        //if there is an memory tool - configure it
        
        if($this->commandLine->hasOption('m') || $this->commandLine->hasOption('mtool')) {
            $this->memoryTool = rtMemoryTool::getInstance($this);
            $this->memoryTool->checkAvailable($this);
            $this->memoryTool->init($this);
            
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
     * required for testing - also used by memory tools
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
    
    public function hasMemoryTool() {
        if($this->memoryTool != null) {
            return true;
        }
        return false;
    }
    
    public function getMemoryToolCommand() {
        return $this->memoryTool->getCommand();
    }
    
    public function setTaskWeightings() {      
        $this->taskWeightings =  rtUtil::readConfigurationFile(__DIR__ .self::WEIGHT_FILE);  
    }     
	  
    
    public function hasWeight($k) {
        return array_key_exists($k, $this->taskWeightings);
    }
    
    public function getWeight($k) {
        return $this->taskWeightings[$k];
    }
}
?>
