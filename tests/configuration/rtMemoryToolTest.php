<?php
require_once dirname(__FILE__) . '../../../src/rtAutoload.php';

class rtMemoryToolTest extends PHPUnit_Framework_TestCase
{


    public function testCreate()
    {

        $config = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-m', 'test.phpt'));
        $this->memoryTool = rtMemoryTool::getInstance($config);
        $this->memoryTool->checkAvailable($config);
        $this->memoryTool->init($config);

        $string = substr($this->memoryTool->getCommand(), 0, 48);

        $this->assertEquals('valgrind -q --tool=memcheck --trace-children=yes', $string);
         
    }

    public function testCreate2()
    {

        $config = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-m', '--mopts', '"blah blah"', 'test.phpt'));
        $this->memoryTool = rtMemoryTool::getInstance($config);
        $this->memoryTool->checkAvailable($config);
        $this->memoryTool->init($config);

        $string = substr($this->memoryTool->getCommand(), 0, 58);
        $this->assertEquals('valgrind -q --tool=memcheck --trace-children=yes blah blah', $string);
         
    }

}
?>

