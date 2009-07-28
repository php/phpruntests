<?php
require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__) . '../../../src/rtAutoload.php';

class rtExternalToolTest extends PHPUnit_Framework_TestCase
{


    public function testCreate()
    {

        $config = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-m', 'test.phpt'));
        $this->externalTool = rtExternalTool::getInstance($config);
        $this->externalTool->checkAvailable($config);
        $this->externalTool->init($config);

        $string = substr($this->externalTool->getCommand(), 0, 48);

        $this->assertEquals('valgrind -q --tool=memcheck --trace-children=yes', $string);
         
    }

    public function testCreate2()
    {

        $config = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-m', '--mopts', '"blah blah"', 'test.phpt'));
        $this->externalTool = rtExternalTool::getInstance($config);
        $this->externalTool->checkAvailable($config);
        $this->externalTool->init($config);

        $string = substr($this->externalTool->getCommand(), 0, 58);
        $this->assertEquals('valgrind -q --tool=memcheck --trace-children=yes blah blah', $string);
         
    }

}
?>

