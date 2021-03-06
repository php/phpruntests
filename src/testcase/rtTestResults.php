<?php
/**
 * rtTestResults
 *
 * Class to store test results.
 *
 * Ensures that files are either deleted or saved depending on the test results
 * Maintains an array of files names that have been saved
 * Maintains (adds to) the tests case status to give a final status array
 * Request calculation of difference between expected/actual output if the test has failed.
 *
 * @category   Testing
 * @package    RUNTESTS
 * @author     Zoe Slattery <zoe@php.net>
 * @author     Stefan Priebsch <spriebsch@php.net>
 * @copyright  2009 The PHP Group
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 *
 */
class rtTestResults
{
    protected $testStatus;
    protected $testName = '';
    protected $title = '';
    protected $time = 0;
    protected $redirectedTest;

    public function __construct(rtPhpTest $testCase = null, rtTestStatus $testStatus = null) 
    {
        $this->init($testCase, $testStatus);
    }

    public function init(rtPhpTest $testCase = null, rtTestStatus $testStatus = null)
    {
        if ($testCase != null) {
            $this->title = $testCase->getSection('TEST')->getHeader();
            $this->testStatus = $testCase->getStatus(); //is an object
            $this->testName = $testCase->getName();
        } else {
            $this->testStatus = $testStatus;
            $this->testName = $testStatus->getTestName();
        }
    }

    public function processResults(rtPhpTest $testCase, rtRuntestsConfiguration $runConfiguration)
    {

        if ($testCase->getStatus()->getValue('pass') == true) {
            $this->onPass($testCase, $runConfiguration);
        } elseif($testCase->getStatus()->getValue('fail') == true) {
            $this->onFail($testCase);
        } elseif ($testCase->getStatus()->getValue('skip') == true) {
            $this->onSkip($testCase, $runConfiguration);
        } else {
            echo "no status? something wrong here\n";
        }
        
        //Deal with mem files generated by memory checking tools. Save or not, irrespective of status
        
        if(file_exists($this->testName . '.mem')) {
            if(filesize($this->testName . '.mem') > 0) {
                $this->testStatus->setSavedFileName('mem', $this->testName. ".mem");
            } else {
                @unlink($this->testName . '.mem');
            }  
        }
        
        //always delete temporary files used in POST sections
        if(file_exists($this->testName . '.post')) {
            @unlink($this->testName . '.post');
        }
        
        
    }

    /**
     * Actions if a test passes
     */
    protected function onPass(rtPhpTest $testCase, rtRuntestsConfiguration $runConfiguration)
    {
        if (!$runConfiguration->hasCommandLineOption('keep-all') && !$runConfiguration->hasCommandLineOption('keep-php')) {
            $testCase->getFileSection()->deleteFile();
        } else {
            $this->testStatus->setSavedFileName('php', $this->testName. ".php");
        }

        if ($runConfiguration->hasCommandLineOption('keep-all') || $runConfiguration->hasCommandLineOption('keep-out')) {
            $outputFileName = $this->testName.".out";
            file_put_contents($outputFileName, $testCase->getOutput());
            $this->testStatus->setSavedFileName('out', $outputFileName);
        }

        if ($runConfiguration->hasCommandLineOption('keep-all') || $runConfiguration->hasCommandLineOption('keep-exp')) {
            $expectedFileName = $this->testName.".exp";
            file_put_contents($expectedFileName, implode(b"\n", $testCase->getExpectSection()->getContents()));
            $this->testStatus->setSavedFileName('exp', $expectedFileName);
        }

        if ($testCase->hasSection('XFAIL')) {
            $this->testStatus->setTrue('warn');
            $this->testStatus->setMessage('warn', 'Test passes but has an XFAIL section');
        }

        if ($testCase->hasSection('CLEAN')) {
            if (!$runConfiguration->hasCommandLineOption('keep-all') && !$runConfiguration->hasCommandLineOption('keep-clean')) {
                $testCase->getSection('CLEAN')->deleteFile();
            } else {
            	$this->testStatus->setSavedFileName('claen', $this->testName. ".clean.php");
            }
        }

        if ($testCase->hasSection('SKIPIF')) {
            $testCase->getSection('SKIPIF')->deleteFile();
        }
        
        if($testCase->getStatus()->getValue('leak') == true) {
            $this->testStatus->setSavedFileName('mem', $testCase->getSection('FILE')->getMemFileName());
        }
        
    }

    protected function onFail(rtPhpTest $testCase)
    {
        $testDifference = new rtTestDifference($testCase->getExpectSection(), $testCase->getOutput());
        $difference = implode(b"\n",$testDifference->getDifference());

        $differenceFileName = $this->testName.".diff";
        $outputFileName = $this->testName.".out";
        $expectedFileName = $this->testName.".exp";

        file_put_contents($differenceFileName, $difference);
        file_put_contents($outputFileName, $testCase->getOutput());
        file_put_contents($expectedFileName, implode(b"\n", $testCase->getExpectSection()->getContents()));

        $this->testStatus->setSavedFileName('out', $outputFileName);
        $this->testStatus->setSavedFileName('exp', $expectedFileName);
        $this->testStatus->setSavedFileName('diff', $differenceFileName);
         
        if ($testCase->hasSection('XFAIL')) {
            $this->testStatus->setTrue('xfail');
            $this->testStatus->setMessage('xfail', $testCase->getSection('XFAIL')->getReason());
            
        }

        //Note: if there are clean and skipif files they will not be deleted if the test fails
        if ($testCase->hasSection('CLEAN')) {
        	$this->testStatus->setSavedFileName('clean', $this->testName. '.clean.php' );
        }

        if ($testCase->hasSection('SKIPIF')) {
        	$this->testStatus->setSavedFileName('skipif', $this->testName. '.skipif.php' );
        }
        
        if($testCase->getStatus()->getValue('leak') == true) {
        	$this->testStatus->setSavedFileName('mem', $testCase->getSection('FILE')->getMemFileName());
        }
    }

    protected function onSkip(rtPhpTest $testCase, rtRuntestsConfiguration $runConfiguration)
    {
        if ($runConfiguration->hasCommandLineOption('keep-all') || $runConfiguration->hasCommandLineOption('keep-skip')) {
        	$this->testStatus->setSavedFileName('skipif', $this->testName. '.skipif.php' );
        } else if($testCase->hasSection('SKIPIF')) {
            $testCase->getSection('SKIPIF')->deleteFile();
        }
    }

    
    
    public function getStatus()
    {
        return $this->testStatus;
    }


    public function getName()
    {
        return $this->testName;
    }

    public function getTitle()
    {
        return $this->title;
    }
    
    public function getTime()
    {
    	return $this->time;
    }
    
    public function setTime($time)
    {
    	$this->time = $time;
    }
    public function getRedirectedTestCase() {
    	return $this->redirectedTest;
    }
}
?>
