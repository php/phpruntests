<?php

require_once dirname(__FILE__) . '../../../../src/rtAutoload.php';

class rtIsPhpVersionCorrectTest extends PHPUnit_Framework_TestCase
{
    private $versionCheck = null;

    protected function setUp()
    {
        $this->versionCheck = new rtIsPhpVersionCorrect();
    }
    
    protected function tearDown()
    {
        $this->versionCheck = null;
    }
    
    public function testCorrectVersion()
    {
        $str = "PHP 5.3.0RC1 (cli) (built: Apr 26 2009 02:49:56) 
                Copyright (c) 1997-2009 The PHP Group
                Zend Engine v2.3.0, Copyright (c) 1998-2009 Zend Technologies";

        $this->assertTrue($this->versionCheck->parseVersionString($str));
        $this->assertTrue($this->versionCheck->parseVersionString("PHP 6.2.0"));
    }
    
    public function testIncorrectVersion()
    {
        $str = "PHP 5.2.9 (cli) (built: Apr 26 2009 03:15:54) 
                Copyright (c) 1997-2009 The PHP Group
                Zend Engine v2.2.0, Copyright (c) 1998-2009 Zend Technologies";
       
        $this->assertFalse($this->versionCheck->parseVersionString($str));
        $this->assertFalse($this->versionCheck->parseVersionString("PHP 4.4.9"));
    }
    
    public function testInvalidString()
    {
        $this->assertFalse($this->versionCheck->parseVersionString("5.3.0"));
        $this->assertFalse($this->versionCheck->parseVersionString("PHP 5.2 PHP 6.0"));
    }
}
?>
