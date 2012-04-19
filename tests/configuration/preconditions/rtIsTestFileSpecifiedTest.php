<?php
/**
 * rtIsTestFileSpecifiedTest
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
 * Tests for rtIsTestFileSpecifiedTest precondition.
 *
 * @category  Testing
 * @package   RUNTESTS
 * @author    Zoe Slattery <zoe@php.net>
 * @author    Stefan Priebsch <spriebsch@php.net>
 * @copyright 2009 The PHP Group
 * @license   http://www.php.net/license/3_01.txt PHP License 3.01
 * @link      http://qa.php.net/
 */
class rtIsTestFileSpecifiedTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->preCondition = new rtIsTestFileSpecified();
    }

    protected function tearDown()
    {
        unset($this->preCondition);
    }

    public function testClOptionR()
    {
        $runtestsConfiguration = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-r', 'some-file'));

        $this->assertTrue($this->preCondition->check($runtestsConfiguration));
    }

    public function testCLOptionL()
    {
        $runtestsConfiguration = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-l', 'some-file'));

        $this->assertTrue($this->preCondition->check($runtestsConfiguration));
    }

    public function testCLOptionFileName()
    {
        $runtestsConfiguration = rtRuntestsConfiguration::getInstance(array('run-tests.php', 'some-test-file'));

        $this->assertTrue($this->preCondition->check($runtestsConfiguration));
    }

    public function testEnvVar()
    {
        $runtestsConfiguration = rtRuntestsConfiguration::getInstance(array());
        $runtestsConfiguration->setEnvironmentVariable('TEST_PHP_USER', 'some-file');

        $this->assertTrue($this->preCondition->check($runtestsConfiguration));
    }

    public function testNotSpecified()
    {
        $runtestsConfiguration = rtRuntestsConfiguration::getInstance(array());

        $this->assertFalse($this->preCondition->check($runtestsConfiguration));
    }

    public function testGetMessage()
    {
        $this->assertEquals($this->preCondition->getMessage('missingTestFile'), rtText::get('missingTestFile'));
    }
}
?>
