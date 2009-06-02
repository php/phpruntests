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
    private $testStatus;
    private $testName = '';
    private $savedResultsFiles = array();
    private $title = '';

    public function __construct(rtPhpTest $testCase = null, rtTestStatus $testStatus = null) 
    {
        $this->init($testCase, $testStatus);
    }

    public function init(rtPhpTest $testCase = null, rtTestStatus $testStatus = null)
    {
        if ($testCase != null) {
            $this->title = implode('',$testCase->getSection('TEST')->getContents());
            $this->testStatus = $testCase->getStatus();
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
    }

    /**
     * Actions if a test passes
     */
    private function onPass(rtPhpTest $testCase, rtRuntestsConfiguration $runConfiguration)
    {
        if (!$runConfiguration->hasCommandLineOption('keep-all') && !$runConfiguration->hasCommandLineOption('keep-php')) {
            $testCase->getFileSection()->deleteFile();
        } else {
            $this->savedFileNames['php'] = $this->testName. ".php";
        }

        if ($runConfiguration->hasCommandLineOption('keep-all') || $runConfiguration->hasCommandLineOption('keep-out')) {
            $outputFileName = $this->testName.".out";
            file_put_contents($outputFileName, $testCase->getOutput());
            $this->savedFileNames['out'] = $outputFileName;
        }

        if ($runConfiguration->hasCommandLineOption('keep-all') || $runConfiguration->hasCommandLineOption('keep-exp')) {
            $expectedFileName = $this->testName.".exp";
            file_put_contents($expectedFileName, implode(b"\n", $testCase->getExpectSection()->getContents()));
            $this->savedFileNames['exp'] = $expectedFileName;
        }

        if ($testCase->hasSection('XFAIL')) {
            $this->testStatus->setTrue('warn');
            $this->testStatus->setMessage('warn', 'Test passes but has an XFAIL section');
        }

        if ($testCase->hasSection('CLEAN')) {
            if (!$runConfiguration->hasCommandLineOption('keep-all') && !$runConfiguration->hasCommandLineOption('keep-clean')) {
                $testCase->getSection('CLEAN')->deleteFile();
            } else {
                $this->savedFileNames['clean'] = $this->testName. ".clean";
            }
        }

        if ($testCase->hasSection('SKIPIF')) {
            $testCase->getSection('SKIPIF')->deleteFile();
        }
    }

    private function onFail(rtPhpTest $testCase)
    {
        $testDifference = new rtTestDifference($testCase->getExpectSection(), $testCase->getOutput());
        $difference = implode(b"\n",$testDifference->getDifference());

        $differenceFileName = $this->testName.".diff";
        $outputFileName = $this->testName.".out";
        $expectedFileName = $this->testName.".exp";

        file_put_contents($differenceFileName, $difference);
        file_put_contents($outputFileName, $testCase->getOutput());
        file_put_contents($expectedFileName, implode(b"\n", $testCase->getExpectSection()->getContents()));

        $this->savedFileNames['out'] = $outputFileName;
        $this->savedFileNames['exp'] = $expectedFileName;
        $this->savedFileNames['diff'] = $differenceFileName;
         
        if ($testCase->hasSection('XFAIL')) {
            $this->testStatus->setTrue('xfail');
            $this->testStatus->setMessage('xfail', $testCase->getSection('XFAIL')->getReason());
            
        }

        //Note: if there are clean and skipif files they will not be deleted if the test fails
        if ($testCase->hasSection('CLEAN')) {
            $this->savedFileNames['clean'] = $this->testName. 'clean.php';
        }

        if ($testCase->hasSection('SKIPIF')) {
            $this->savedFileNames['skipif'] = $this->testName. 'skipif.php';
        }
    }

    private function onSkip(rtPhpTest $testCase, rtRuntestsConfiguration $runConfiguration)
    {
        if ($runConfiguration->hasCommandLineOption('keep-all') || $runConfiguration->hasCommandLineOption('keep-skip')) {
            $this->savedFileNames['skipif'] = $this->testName. 'skipif.php';
        } else {
            $skipSection = $testCase->getSection('SKIPIF');
            $skipSection->deleteFile();
        }
        
        //It may seem odd to check for an XFAIL if we are skipping the test, on the other hand I found
        //a few windows tests with blank XFAIL sections and wanted to know about those.
        
        if ($testCase->hasSection('XFAIL')) {
            $this->testStatus->setTrue('xfail');
            $this->testStatus->setMessage('xfail',$testCase->getSection('XFAIL')->getReason());
        }
    }

    public function getStatus()
    {
        return $this->testStatus;
    }

    public function getSavedFileNames()
    {
        return $this->savedFileNames;
    }

    public function getName()
    {
        return $this->testName;
    }

    public function getTitle()
    {
        return $this->title;
    }
}
?>
