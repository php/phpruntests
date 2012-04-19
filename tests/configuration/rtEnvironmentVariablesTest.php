<?php

require_once dirname(__FILE__) . '../../../src/rtAutoload.php';

class rtEnvironmentVariablesTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        putenv('TEST_PHP_EXECUTABLE');
        putenv('TEST_PHP_CGI_EXECUTABLE');
        putenv('TEST_PHP_ARGS');
        putenv('TEST_PHP_SRCDIR');
        putenv('TEST_PHP_LOG_FORMAT');
        putenv('TEST_PHP_DETAILED');
        putenv('TEST_PHP_USER');
        putenv('TEST_PHP_PARALLEL');
        putenv('NO_INTERACTION');
        putenv('PHP_AUTOCONF');
        putenv('TEST_PHP_ARGS');
        putenv('REPORT_EXIT_STATUS');
        putenv('TEST_PHP_ERROR_STYLE');
        putenv('NO_PHPTEST_SUMMARY');
        putenv('SystemRoot');       
    }

    public function testGetVariable()
    {
        putenv('TEST_PHP_EXECUTABLE=some-executable');
        $ev = rtEnvironmentVariables::getInstance();
        $ev->getUserSuppliedVariables();

        $this->assertEquals('some-executable', $ev->getVariable('TEST_PHP_EXECUTABLE'));
    }

    public function testAdaptUnix()
    {
        putenv('TEST_PHP_PARALLEL=a-parallel-run');
        $ev = rtEnvironmentVariables::getInstance();
        $ev->getUserSuppliedVariables();

        $this->assertEquals('a-parallel-run', $ev->getVariable('TEST_PHP_PARALLEL'));
    }
    
    public function testAdaptWin()
    {
        putenv('SystemRoot=some-windows-thing');
        $ev = rtEnvironmentVariables::getInstance('Windows');
        $ev->getUserSuppliedVariables();
  
        $this->assertEquals('some-windows-thing', $ev->getVariable('SystemRoot'));
    }
}
?>
