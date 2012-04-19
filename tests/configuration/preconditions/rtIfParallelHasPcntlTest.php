<?php
/**
 * rtIfParallelHasPcntlTest
 *
 * @category  Testing
 * @package   RUNTESTS
 * @author    Zoe Slattery <zoe@php.net>
 * @author    Stefan Priebsch <spriebsch@php.net>
 * @copyright 2009 The PHP Group
 * @license   http://www.php.net/license/3_01.txt PHP License 3.01
 * @link      http://qa.php.net/
 */

require_once dirname(__FILE__) . '../../../../src/rtAutoload.php';

/**
 * Tests for rtIfParallelHasPcntlTest precondition.
 *
 * @category  Testing
 * @package   RUNTESTS
 * @author    Zoe Slattery <zoe@php.net>
 * @author    Stefan Priebsch <spriebsch@php.net>
 * @copyright 2009 The PHP Group
 * @license   http://www.php.net/license/3_01.txt PHP License 3.01
 * @link      http://qa.php.net/
 */
class rtIfParallelHasPcntlTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->preCondition = new rtIfParallelHasPcntl();
    }

    protected function tearDown()
    {
        unset($this->preCondition);
    }

    /**
     * Ensure that check (wether pcntl is loaded) works 
     * when parallel test execution is requested by command line option.
     */
    public function testCheckWhenCommandLineOptionIsSet()
    {
        $runtestsConfiguration = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-z', '1'));
        
        $this->assertEquals(extension_loaded('pcntl'), $this->preCondition->check($runtestsConfiguration));
    }

    /**
     * Ensure that check (wether pcntl is loaded) works 
     * when parallel test execution is requested by environment variable.
     */
    public function testCheckWhenEnvironmentVariableIsSet()
    {
        $runtestsConfiguration = rtRuntestsConfiguration::getInstance(array());
        $runtestsConfiguration->setEnvironmentVariable('TEST_PHP_PARALLEL', true);
        
        $this->assertTrue($this->preCondition->check($runtestsConfiguration));
    }

    /**
     * Ensure that check returns true when no parallel test execution is requested.
     */    
    public function testCheckWhenParallelExecutionIsNotRequired()
    {
        $runtestsConfiguration = rtRuntestsConfiguration::getInstance(array());

        $this->assertTrue($this->preCondition->check($runtestsConfiguration));
    }

    /**
     * Ensure that the error message is correct.
     */
    public function testGetMessage()
    {
        $this->assertEquals($this->preCondition->getMessage('pcntlNotLoaded'), rtText::get('pcntlNotLoaded'));
    }
}
?>
