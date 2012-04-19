<?php
/**
 * rtIsExecutableSetTest
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
 * Tests for rtIsExecutableSetTest precondition.
 *
 * @category  Testing
 * @package   RUNTESTS
 * @author    Zoe Slattery <zoe@php.net>
 * @author    Stefan Priebsch <spriebsch@php.net>
 * @copyright 2009 The PHP Group
 * @license   http://www.php.net/license/3_01.txt PHP License 3.01
 * @link      http://qa.php.net/
 */
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

    public function testCheckWhenCommandLineOptionIsSet()
    {
        $runtestsConfiguration = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', 'some-file'));
        
        $this->assertTrue($this->preCondition->check($runtestsConfiguration));
    }

    public function testCheckWhenEnvironmentVariableIsSet()
    {
        $runtestsConfiguration = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', 'some-file'));
        $runtestsConfiguration->setEnvironmentVariable('TEST_PHP_EXECUTABLE', 'some-executable');

        $this->assertTrue($this->preCondition->check($runtestsConfiguration));
    }

    public function testGetMessage()
    {
        $this->assertEquals($this->preCondition->getMessage('missingPHPExecutable'), rtText::get('missingPHPExecutable'));
    } 
}
?>
