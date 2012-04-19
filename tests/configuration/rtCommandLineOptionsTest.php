<?php

require_once dirname(__FILE__) . '../../../src/rtAutoload.php';

class rtCommandLineOptionsTest extends PHPUnit_Framework_TestCase
{
    public function testNoOption() 
    {
        $clo = new rtCommandLineOptions();
        $clo->parse(array('run-tests.php'));
        $fileArray = $clo->getTestFilename();

        $this->assertTrue(is_array($fileArray));
    }

    public function testShortOption()
    {
        $clo = new rtCommandLineOptions();
        $clo->parse(array('run-tests.php', '-n'));

        $this->assertTrue($clo->hasOption('n'));
    }

    public function testLongOption()
    {
        $clo = new rtCommandLineOptions();
        $clo->parse(array('run-tests.php', '--help'));

        $this->assertTrue($clo->hasOption('help'));
    }

    public function testShortOptionWithArg()
    {
        $clo = new rtCommandLineOptions();
        $clo->parse(array('run-tests.php', '-d', 'the-d-arg'));

        $this->assertTrue($clo->hasOption('d'));
        $this->assertEquals('the-d-arg', $clo->getOption('d'));
    }

    public function testLongOptionWithArg()
    {
        $clo = new rtCommandLineOptions();
        $clo->parse(array('run-tests.php', '--mopts', 'the-memoryoptions-arg'));

        $this->assertTrue($clo->hasOption('mopts'));
        $this->assertEquals('the-memoryoptions-arg', $clo->getOption('mopts'));
    }

    public function testNonexistingOption()
    {
        $clo = new rtCommandLineOptions();
        $clo->parse(array('run-tests.php'));

        $this->assertFalse($clo->hasOption('nonexisting'));
        // test for exception when calling getRunOption('nonexisting')?
    }

    /**
     * @expectedException rtException
     */
    public function testMissingShortOptionArgument()
    {
        $clo = new rtCommandLineOptions();
        $clo->parse(array('run-tests.php', '-d'));
        $clo->getOption('d');
    }

    /**
     * @expectedException rtException
     */
    public function testMissingLongOptionArgument()
    {
        $clo = new rtCommandLineOptions();
        $clo->parse(array('run-tests.php', '--mopts'));
        $clo->getOption('--mopts');
    }

    public function testFileArgument()
    {
        $clo = new rtCommandLineOptions();
        $clo->parse(array('run-tests.php', 'the-filename'));
        $fileArray = $clo->getTestFilename();

        $this->assertEquals('the-filename', $fileArray[0]);
    }
    
    public function testManyFileArgument()
    {
        $clo = new rtCommandLineOptions();
        $clo->parse(array('run-tests.php', 'the-filename1', 'the-filename2'));
        $fileArray = $clo->getTestFilename();
  
        $this->assertEquals('the-filename2', $fileArray[1]);
    }

    // a nasty case is a filename starting with - or --
    // the filename should be quoted in that case
}
?>
