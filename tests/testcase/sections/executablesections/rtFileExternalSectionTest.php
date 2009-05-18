<?php

require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__) . '../../../../../src/rtAutoload.php';

class rtFileExternalSectionTest extends PHPUnit_Framework_TestCase
{
    public function testCreateInstance()
    {
        $fileSection = rtFileExternalSection::getInstance('FILE_EXTERNAL', array('<?php', 'echo "hello world";', '?>'));
        $code = $fileSection->getContents();

        $this->assertEquals('<?php', $code[0]);
    }
    
    public function testTooMuchFiles()
    {
        $fileSection = rtFileExternalSection::getInstance('FILE_EXTERNAL', array('file1','file2'));
    	$content = $fileSection->getContents();
    	$config = rtRuntestsConfiguration::getInstance(array());
    	$test = new rtPhpTest($content, 'TEST', array('FILE_EXTERNAL'), $config);

    	$status = $fileSection->run($test, $config);

        $this->assertEquals('One file per testcase permitted.', $status['fail']);
    }
    
    public function testNotExistingFile()
    {
        $fileSection = rtFileExternalSection::getInstance('FILE_EXTERNAL', array('file1'));
        $content = $fileSection->getContents();
        $config = rtRuntestsConfiguration::getInstance(array());
        $test = new rtPhpTest($content, 'TEST', array('FILE_EXTERNAL'), $config);

        $this->assertEquals('Can not open external file /file1', $status['fail']);
    }
}

?>