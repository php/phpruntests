<?php

require_once dirname(__FILE__) . '../../../src/rtAutoload.php';
require_once dirname(__FILE__) . '/../rtTestBootstrap.php';

class rtPhpTestTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->testCase = array (
                            '--TEST--', 
                            'This is a test',
                            '--FILE--',
                            '<?php',
                            ' echo "hello world"; ',
                            '?>',
                            '===Done===',
                            'blah blah blah',
                            '--EXPECTF--',
                            'hello world',
                            '===Done===',
                            'gah',
        );
        
        $this->testCase2 = array (
                            '--TEST--', 
                            'This is a test',
                            '--GET--',
                            '--POST--',
                            '--FILE--',
                            '<?php',
                            ' echo "hello world"; ',
                            '?>',
                            '===Done===',
                            'blah blah blah',
                            '--EXPECTF--',
                            'hello world',
                            '===Done===',
                            'gah',
        );
        
    }

    public function testCreateInstance()
    {
        $config = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', RT_PHP_PATH, 'test.phpt'));
        $config->configure();

        $status = new rtTestStatus('nameOfTest');
        $test = new rtPhpTest($this->testCase, 'nameOfTest', array('TEST', 'FILE', 'EXPECTF'), $config, $status);

        $this->assertEquals('rtPhpTest', get_class($test));
    }

    public function testSections()
    {
        $config = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', RT_PHP_PATH, 'test.phpt'));
        $config->configure();

        $status = new rtTestStatus('nameOfTest');
        $test = new rtPhpTest($this->testCase, 'nameOfTest', array('TEST', 'FILE', 'EXPECTF'), $config, $status);

        $this->assertEquals('rtTestHeaderSection', get_class($test->getSection('TEST')));
        $this->assertEquals('rtFileSection', get_class($test->getSection('FILE')));
        $this->assertEquals('rtExpectFSection', get_class($test->getSection('EXPECTF')));
    } 
    
    public function testDone()
    {
        $config = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', RT_PHP_PATH, 'test.phpt'));
        $config->configure();

        $status = new rtTestStatus('nameOfTest');
        $test = new rtPhpTest($this->testCase, 'nameOfTest', array('TEST', 'FILE', 'EXPECTF'), $config, $status);
        
        $contents = $test->getSection('FILE')->getContents();
        $this->assertEquals('===Done===', end($contents));
        
        $contents = $test->getSection('EXPECTF')->getContents();
        $this->assertEquals('gah', end($contents));

    } 
    
    public function testEmptySection()
    {
        $config = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', RT_PHP_PATH, 'test.phpt'));
        $config->configure();

        $status = new rtTestStatus('nameOfTest');
       // $test = new rtPhpTest($this->testCase2, 'nameOfTest', array('TEST', 'GET', 'POST', 'FILE', 'EXPECTF'), $config, $status);
        
        

    } 
}
?>
