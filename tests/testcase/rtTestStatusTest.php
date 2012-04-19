<?php
require_once dirname(__FILE__) . '../../../src/rtAutoload.php';

class rtTestStatusTest extends PHPUnit_Framework_TestCase {
    function testCreate() 
    {
        $status = new rtTestStatus('aTest');
        $this->assertEquals('aTest', $status->getTestName());
        $this->assertFalse($status->getValue('skip'));
    }
}
?>
