<?php
/**
 * rtPreCondtionListTest
 *
 * @category  Testing
 * @package   RUNTESTS
 * @author    Zoe Slattery <zoe@php.net>
 * @author    Stefan Priebsch <spriebsch@php.net>
 * @copyright 2009 The PHP Group
 * @license   http://www.php.net/license/3_01.txt PHP License 3.01
 * @link      http://qa.php.net/
 */

require_once dirname(__FILE__) . '../../../src/rtAutoload.php';
require_once dirname(__FILE__) . '/../rtTestBootstrap.php';

/**
 * Tests for rtPreCondtionListTest precondition.
 *
 * @category  Testing
 * @package   RUNTESTS
 * @author    Zoe Slattery <zoe@php.net>
 * @author    Stefan Priebsch <spriebsch@php.net>
 * @copyright 2009 The PHP Group
 * @license   http://www.php.net/license/3_01.txt PHP License 3.01
 * @link      http://qa.php.net/
 */
class rtPreCondtionListTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->preConditionList = rtPreConditionList::getInstance();
    }

    protected function tearDown()
    {
        unset($this->preConditionList);
    }
    
    public function testCheck()
    {
        
        $preConditionList = rtPreConditionList::getInstance();
        $runtestsConfiguration = rtRuntestsConfiguration::getInstance(array('run-tests.php', '-p', RT_PHP_PATH, 'a-test.phpt'));
        $runtestsConfiguration->configure();

        $this->assertTrue($preConditionList->check($runtestsConfiguration));
    }

    public function testGetInstanceOnUnix()
    {
        $preConditionList = rtPreConditionList::getInstance('Unix');

        $this->assertTrue($preConditionList instanceOf rtUnixPreConditionList);
    }
    
    public function testGetInstanceOnWindows()
    {
        $preConditionList = rtPreConditionList::getInstance('Windows');

        $this->assertTrue($preConditionList instanceOf rtWinPreConditionList);
    }
}
?>
