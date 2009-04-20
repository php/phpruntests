<?php

require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__) . '../../../../src/rtAutoload.php';

class rtIsProcOpenAvailableTest extends PHPUnit_Framework_TestCase
{
    public function testAvailable()
    {
        $pre = new rtIsProcOpenAvailable();

        $this->assertTrue($pre->check());
    }

    public function testgetMessage()
    {
        $pre = new rtIsProcOpenAvailable();

        $this->assertEquals($pre->getMessage('procOpenNotAvailable'), rtText::get('procOpenNotAvailable'));
    }
    
    //Not sure how to check if it is missing?
}
?>
