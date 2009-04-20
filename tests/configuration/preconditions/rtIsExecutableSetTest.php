<?php

require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__) . '../../../../src/rtAutoload.php';

class rtIsExecutableSetTest extends PHPUnit_Framework_TestCase
{
    public function testClOption()
    {
        $env = rtEnvironmentVariables::getInstance();
        $clo = new rtCommandLineOptions();
        $clo->parse(array('run-tests.php', '-p', 'some-file'));

        $pre = new rtIsExecutableSet();

        $this->assertTrue($pre->check($clo, $env));
    }

    public function testEVOption()
    {
        $env = rtEnvironmentVariables::getInstance();
        $clo = new rtCommandLineOptions();
        $env->setVariable('TEST_PHP_EXECUTABLE', 'some-executable');

        $pre = new rtIsExecutableSet();

        $this->assertTrue($pre->check($clo,$env));
    }

    public function testGetText()
    {
        $pre = new rtIsExecutableSet();

        $this->assertEquals($pre->getMessage('missingPHPExecutable'), rtText::get('missingPHPExecutable'));
    } 
}
?>
