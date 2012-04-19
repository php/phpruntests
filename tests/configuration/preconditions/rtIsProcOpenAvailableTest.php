<?php
/**
 * rtIsProcOpenAvailableTest
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
 * Tests for rtIsProcOpenAvailableTest precondition.
 *
 * @category  Testing
 * @package   RUNTESTS
 * @author    Zoe Slattery <zoe@php.net>
 * @author    Stefan Priebsch <spriebsch@php.net>
 * @copyright 2009 The PHP Group
 * @license   http://www.php.net/license/3_01.txt PHP License 3.01
 * @link      http://qa.php.net/
 */
class rtIsProcOpenAvailableTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->preCondition = new rtIsProcOpenAvailable();
    }

    protected function tearDown()
    {
        unset($this->preCondition);
    }

    public function testCheck()
    {
        $runtestsConfiguration = rtRuntestsConfiguration::getInstance(array());

        $this->assertEquals(function_exists('proc_open'), $this->preCondition->check($runtestsConfiguration));
    }

    public function testgetMessage()
    {
        $pre = new rtIsProcOpenAvailable();

        $this->assertEquals($pre->getMessage('procOpenNotAvailable'), rtText::get('procOpenNotAvailable'));
    }
}
?>
