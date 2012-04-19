<?php
/**
 * rtIsPcreLoadedTest
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
 * Tests for rtIsPcreLoadedTest precondition.
 *
 * @category  Testing
 * @package   RUNTESTS
 * @author    Zoe Slattery <zoe@php.net>
 * @author    Stefan Priebsch <spriebsch@php.net>
 * @copyright 2009 The PHP Group
 * @license   http://www.php.net/license/3_01.txt PHP License 3.01
 * @link      http://qa.php.net/
 */
class rtIsPcreLoadedTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->preCondition = new rtIsPcreLoaded();
    }

    protected function tearDown()
    {
        unset($this->preCondition);
    }

    public function testCheck()
    {
        $runtestsConfiguration = rtRuntestsConfiguration::getInstance(array());

        $this->assertEquals(extension_loaded('pcre'), $this->preCondition->check($runtestsConfiguration));
    }
  
    public function testGetMessage()
    {
        $this->assertEquals($this->preCondition->getMessage('pcreNotLoaded'), rtText::get('pcreNotLoaded'));
    }
}
?>
