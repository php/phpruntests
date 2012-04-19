<?php

require_once dirname(__FILE__) . '../../../../../src/rtAutoload.php';

class rtEnvSectionTest extends PHPUnit_Framework_TestCase
{
    public function testCreateInstance()
    {
        $envSection = rtEnvSection::getInstance('ENV', array('env1 = ENV1', 'env2=ENV2'), 'testname');
        $envlist = $envSection->getTestEnvironmentVariables();

        $this->assertEquals('ENV1', $envlist['env1']);
        $this->assertEquals('ENV2', $envlist['env2']);
    }

    public function testCreateInstance2()
    {
        $envSection = rtEnvSection::getInstance('ENV', array('env1 = ENV1=env'), 'testname');
        $envlist = $envSection->getTestEnvironmentVariables();

        $this->assertEquals('ENV1=env', $envlist['env1']);
       
    }
}
?>
