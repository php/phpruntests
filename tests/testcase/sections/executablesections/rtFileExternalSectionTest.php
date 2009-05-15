<?php

require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__) . '../../../../../src/rtAutoload.php';

class rtFileExternalSectionTest extends PHPUnit_Framework_TestCase
{
    public function testCreateInstance()
    {
        $fileSection = new rtFileExternalSection('FILE_EXTERNAL', array('<?php', 'echo "hello world";', '?>'));
        $code = $fileSection->getContents();

        $this->assertEquals('<?php', $code[0]);
    }
    
    public function testTooMuchFiles()
    {
        $wrapper = new rtFileExternalSectionTestWrapper('FILE_EXTERNAL', array('file1','file2'));
    	
        $this->assertFalse($wrapper->copyExternalFileContentTest());
        
        $status = $wrapper->getStatus();

        $this->assertEquals('One file per testcase permitted.', $status['fail']);
    }
    
    public function testNotExistingFile()
    {
        $wrapper = new rtFileExternalSectionTestWrapper('FILE_EXTERNAL', array('file1'));
        
        $this->assertFalse($wrapper->copyExternalFileContentTest());
        
        $status = $wrapper->getStatus();

        $this->assertEquals('Can not open external file /file1', $status['fail']);
    }
}

/**
 * test-wrapper to acces protected methods and members
 */
class rtFileExternalSectionTestWrapper extends rtFileExternalSection
{
    public function copyExternalFileContentTest()
    {
    	return parent::copyExternalFileContent();
    }

    public function getStatus()
    {
        return $this->status;
    }
}

?>