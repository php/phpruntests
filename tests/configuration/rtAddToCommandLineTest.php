<?php

require_once dirname(__FILE__) . '../../../src/rtAutoload.php';

class rtAddToCommandLineTest extends PHPUnit_Framework_TestCase
{
    public function testAdd() 
    {
        $commandLine = new rtCommandLineOptions();
        $commandLine->parse(array('run-tests.php', '-n'));
        
        $env = rtEnvironmentVariables::getInstance();
        $env->setVariable('TEST_PHP_ARGS', '-p a-php-executable');
        
        $commandLineAdd = new rtAddToCommandLine();
        $commandLineAdd->parseAdditionalOptions($commandLine, $env);
        
        $this->assertTrue($commandLine->hasOption('p'));
    }
}
?>
