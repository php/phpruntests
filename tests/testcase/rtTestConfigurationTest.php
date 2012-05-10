<?php

require_once dirname(__FILE__) . '../../../src/rtAutoload.php';
require_once dirname(__FILE__) . '/../rtTestBootstrap.php';

class rtTestConfigurationTest extends PHPUnit_Framework_TestCase
{
    private $sections;

    public function setUp()
    {
        
        $this->sections['ARGS'] = rtArgsSection::getInstance('ARGS', array('-vvv -a value -1111 -2 -v'), 'testname');
        $this->sections['ENV'] = rtEnvSection::getInstance('ENV', array('env1 = ENV1', 'env2=ENV2'), 'testname');
        $this->sections['INI'] = rtIniSection::getInstance('INI', array('error_reporting=E_ALL | E_STRICT | E_DEPRECATED', 'assert.active = 1'), 'testname');
        $this->sections['FILE'] = rtFileSection::getInstance('FILE', array('blah'), 'testname');
        
    }

    public function testCreateInstance()
    {
        $config = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', RT_PHP_PATH, 'test.phpt'));
        $config->configure();

        $testConfiguration = new rtTestConfiguration($config, $this->sections, array(),$this->sections['FILE']);

        $this->assertEquals('rtTestConfiguration', get_class($testConfiguration));
    }

    public function testEnv()
    {
        $config = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', RT_PHP_PATH, 'test.phpt'));
        $config->configure();

        $testConfiguration = new rtTestConfiguration($config, $this->sections, array(), $this->sections['FILE']);

        $envVars = $testConfiguration->getEnvironmentVariables();

        $this->assertEquals('ENV1', $envVars['env1']);
    }

    public function testArgs()
    {
        $config = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', RT_PHP_PATH, 'test.phpt'));
        $config->configure();

        $testConfiguration = new rtTestConfiguration($config, $this->sections, array(),$this->sections['FILE']);

        $args = $testConfiguration->getTestCommandLineArguments();

        $this->assertEquals('-- -vvv -a value -1111 -2 -v', $args);
    }

    public function testIni()
    {
        $config = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', RT_PHP_PATH, 'test.phpt'));
        $config->configure();

        $testConfiguration = new rtTestConfiguration($config, $this->sections, array(),$this->sections['FILE']);
        $phpargs = $testConfiguration->getPhpCommandLineArguments();
        $match = preg_match("/-d \"error_reporting=E_ALL | E_STRICT | E_DEPRECATED\" -d \"assert.active=1\"/", $phpargs);

        $this->assertEquals(1, $match);
    }

    public function testPHPExecutable()
    {
        $config = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', RT_PHP_PATH, 'test.phpt'));
        $config->configure();
 
        $testConfiguration = new rtTestConfiguration($config, $this->sections, array(),$this->sections['FILE']);
        $phpExe = $testConfiguration->getPhpExecutable();

        $this->assertEquals(RT_PHP_PATH, $phpExe);
    }

    public function testPHPCgiExecutable()
    {
        $config = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', RT_PHP_PATH, 'test.phpt'));
        $config->setEnvironmentVariable('TEST_PHP_CGI_EXECUTABLE', 'a-php-cgi-exe');
        $config->configure();
  

        $testConfiguration = new rtTestConfiguration($config, $this->sections, array('GET'),$this->sections['FILE']);
        $phpExe = $testConfiguration->getPhpExecutable();

        $this->assertEquals('a-php-cgi-exe -C', $phpExe);
    }
}
?>
