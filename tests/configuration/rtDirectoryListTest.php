<?php

require_once dirname(__FILE__) . '../../../src/rtAutoload.php';

class rtDirectoryListTestTest extends PHPUnit_Framework_TestCase
{  
    public function setUp()
    {
        $tmpDir = sys_get_temp_dir(). "/top";
        mkdir($tmpDir);
        mkdir($tmpDir. "/a");
        mkdir($tmpDir. "/b");
        mkdir($tmpDir. "/c");
        file_put_contents($tmpDir. "/c/afile", "hello"); 
        mkdir($tmpDir. "/c/d");  
    }
        
    public function tearDown()
    {
        $tmpDir = sys_get_temp_dir(). "/top";
        rmdir($tmpDir. "/a");
        rmdir($tmpDir. "/b");
        
        unlink($tmpDir. "/c/afile");
        rmdir($tmpDir. "/c/d"); 
        rmdir($tmpDir. "/c");  
        rmdir($tmpDir);   
    }
    
    public function testNames()
    {
        $directoryList = new rtDirectoryList();  
        $top = sys_get_temp_dir(). "/top";
        $list = $directoryList->getSubDirectoryPaths($top);
        $this->assertEquals(true, in_array($top . "/a", $list));
        $this->assertEquals(true, in_array($top . "/c/d", $list));
    }
        
    public function testList()
    {
        $directoryList = new rtDirectoryList();  
        $top = sys_get_temp_dir(). "/top";
        $list = $directoryList->getSubDirectoryPaths($top);
        $this->assertEquals(5, count($list));
    }
}
?>
