<?php
/**
 * rtTestRun
 *
 * Main class for a single test run
 *
 * @category   Quality Assurance
 * @package    run-tests
 * @author     Zoe Slattery <zoe@php.net>
 * @author     Stefan Priebsch <spriebsch@php.net>
 * @copyright  2009 The PHP Group
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 *
 */
class rtPhpTestRun
{
    private $commandLineArguments;

    public function __construct($argv)
    {
        $this->commandLineArguments = $argv;
    }

    public function run()
    {
        //Set SSH variables

        //Configure the test environment
        $runConfiguration = rtRuntestsConfiguration::getInstance($this->commandLineArguments);
        $runConfiguration->getUserEnvironment();
        $runConfiguration->configure();

        //Check the preconditions
        $preConditionList = rtPreConditionList::getInstance();

        // $preConditionList->check($this->commandLine, $this->environmentVariables);
        $preConditionList->check($runConfiguration);

        if ($runConfiguration->getSetting('TestDirectories') != null) {

            foreach ($runConfiguration->getSetting('TestDirectories') as $testDirectory) {

                //make list of subdirectories which contain tests, includes the top level directory
                $subDirectories = rtUtil::getDirectoryList($testDirectory);

                //Run tests in each subdirectory in sequence
                foreach ($subDirectories as $subDirectory) {
                    $testGroup = new rtPhpTestGroup($runConfiguration, $subDirectory);
                    $testGroup->runGroup($runConfiguration);
                    $testGroup->writeGroup();
                }
            }

            //*have a directory or list of directories to test.
            /* if (single directory) {
            * 	if(contains sub-directories with .phpt files) {
            * 		if (parallel) {
            * 			initate parallel run
            *        } else {
            *            initaite sequential run
            *        }
            *    } else {
            *        initiate sequential run
            *    }
            * } else {    //multiple directories
            *    if (parallel) {
            *       initiate parallel (runs the list of dirs in parallel
            *    } else {
            *       run each directory in sequence
            *    }
            * }
            *
            */
        } else {

            if ($runConfiguration->getSetting('TestFiles') == null) {
                echo rtText::get('invalidTestFileName');
                exit();
            } else {
                foreach ($runConfiguration->getSetting('TestFiles') as $testName) {

                    if (!file_exists($testName)) {
                        echo rtText::get('invalidTestFileName', array($testName));
                        exit();
                    }

                    //Read the test file
                    $testFile = new rtPhpTestFile();
                    $testFile->doRead($testName);
                    $testFile->normaliseLineEndings($testName);

                    if ($testFile->arePreconditionsMet()) {

                        $testCase = new rtPhpTest($testFile->getContents(), $testFile->getTestName(), $testFile->getSectionHeadings(), $runConfiguration);
                         
                        //Setup and set the local environment for the test case
                        $testCase->executeTest($runConfiguration);

                        $results = new rtTestResults($testCase);
                        $results->processResults($testCase, $runConfiguration);

                    } else {
                        $results = new rtTestResults(null, $testFile->getExitMessage(), $testFile->getTestName());
                    }

                    $testOutputWriter = rtTestOutputWriter::getInstance(array($results), 'list');
                    $testOutputWriter->write();
                }
            }
        }
    }
}
?>
