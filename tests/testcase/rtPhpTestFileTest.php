<?php

require_once dirname(__FILE__) . '../../../src/rtAutoload.php';

class rtPhpTestFileTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->path_to_tests = realpath(dirname(__FILE__) . '/../../phpt-tests');
        $this->sample_test = $this->path_to_tests . '/sample_test.phpt';
        $this->sample_extra_char = $this->path_to_tests. '/sample_extra_char.phpt';
        $this->sample_windows_test = $this->path_to_tests . '/sample_windows_test.phpt';
        $this->sample_es_test = $this->path_to_tests . '/sample_empty_sections.phpt';
        
    }
        
    public function testCreateInstance()
    {
        $testFile = new rtPhpTestFile();

        $this->assertTrue(is_object($testFile));
    }
     
    public function testReadGoodFile()
    {
        $testFile = new rtPhpTestFile();
        $testFile->doRead($this->sample_test);
        $testFile->normaliseLineEndings();
     
        $fileArray = $testFile->getContents();
        $this->assertEquals('--TEST--', $fileArray[0]);

        $this->assertEquals($this->path_to_tests."/sample_test", $testFile->getTestName());
    }
     
    public function testWindowsLineEndings()
    {
        $testFile = new rtPhpTestFile();
        $testFile->doRead($this->sample_windows_test);
        $testFile->normaliseLineEndings();
   
        
        $fileArray = $testFile->getContents();
        $fileString = implode(' ', $fileArray);

        $unmodified = file_get_contents($this->sample_windows_test);

        //Check that there are windows line endings in the original
        $this->assertEquals(preg_match('/\r\n/', $unmodified), 1); 

        //..and that they have been successfully removed
        $this->assertEquals(preg_match("/\r\n/", $fileString), 0);
    }
     
    public function testLinuxLineEndings()
    {
        $testFile = new rtPhpTestFile();
        $testFile->doRead($this->sample_test);
        $testFile->normaliseLineEndings();
     
        $fileArray = $testFile->getContents();
        $fileString = implode(' ', $fileArray);

        $unmodified = file_get_contents($this->sample_test);

        //Check that there are windows line endings in the original
        $this->assertEquals(preg_match('/\n/', $unmodified), 1); 

        //..and that they have been successfully removed
        $this->assertEquals(preg_match("/\n/", $fileString), 0);
    }
     
    public function testPreconditionCall()
    {
        $testFile = new rtPhpTestFile();
        $testFile->doRead($this->sample_test);
        $testFile->normaliseLineEndings();


        $this->assertTrue($testFile->arePreConditionsMet());       
    } 
}
?>
