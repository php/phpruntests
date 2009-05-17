<?php

require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__) . '../../../../../src/rtAutoload.php';

class rtEnvSectionTest extends PHPUnit_Framework_TestCase
{
    public function testCreateInstance() 
    {
        $envSection = rtEnvSection::getInstance('ENV', array('env1 = ENV1', 'env2=ENV2'));  
        $envlist = $envSection->getTestEnvironmentVariables();

        $this->assertEquals('ENV1', $envlist['env1']);
        $this->assertEquals('ENV2', $envlist['env2']);
    }
}
?>
