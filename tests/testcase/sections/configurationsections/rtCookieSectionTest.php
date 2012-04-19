<?php
require_once dirname(__FILE__) . '../../../../../src/rtAutoload.php';

class rtCookieSectionTest extends PHPUnit_Framework_TestCase
{
    public function testCreateInstance() 
    {
        $cookieSection = rtCookieSection::getInstance('COOKIE', array('hello=World&goodbye=MrChips'), 'testname');  
        $envlist = $cookieSection->getCookieVariables();

        $this->assertEquals('hello=World&goodbye=MrChips', $envlist['HTTP_COOKIE']);
    }
}
?>
