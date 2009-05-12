<?php

require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__) . '../../../../src/rtAutoload.php';

class rtIsExecutableSetTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->preCondition = new rtIsExecutableSet();
    }

    protected function tearDown()
    {
        unset($this->preCondition);
    }

    public function testClOption()
    {
        $runtestsConfiguration = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', 'some-file'));
        
        $this->assertTrue($this->preCondition->check($runtestsConfiguration));
    }

    public function testEVOption()
    {
        $runtestsConfiguration = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', 'some-file'));
        $runtestsConfiguration->setEnvironmentVariable('TEST_PHP_EXECUTABLE', 'some-executable');

        $this->assertTrue($this->preCondition->check($runtestsConfiguration));
    }

    public function testGetText()
    {
        $this->assertEquals($this->preCondition->getMessage('missingPHPExecutable'), rtText::get('missingPHPExecutable'));
    } 
}
?>
