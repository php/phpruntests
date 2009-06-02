<?php
require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__) . '../../../src/rtAutoload.php';

class rtTestStatusTest extends PHPUnit_Framework_TestCase {
    function testCreate() 
    {
        $status = new rtTestStatus();
        $this->assertFalse($status->getValue('skip'));
    }
}
?>