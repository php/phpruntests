<?php

require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__) . '../../../src/rtAutoload.php';

class rtPreCondtionListTest extends PHPUnit_Framework_TestCase
{
    protected $clo;
    protected $env;
    protected $ge;
    
    public function setUp()
    {
        $this->clo = new rtCommandLineOptions();
        $this->clo->parse(array('run-tests.php', '-p', 'some-php-exe', 'a-test.phpt'));

        $this->env = rtEnvironmentVariables::getInstance();
        $this->env->getUserSuppliedVariables();      
    }
    
    public function testCheck()
    {
        $sl = rtPreConditionList::getInstance();

        $this->assertTrue($sl->check($this->clo, $this->env));
    }
    
    public function testUnix()
    {
        $sl = rtPreConditionList::getInstance();
        $sl->adaptList();

        $this->assertTrue($sl->hasPreCondition('rtIfParallelHasPcntl'));
    }
}
?>
