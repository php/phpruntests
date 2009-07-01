<?php
/**
 * rtPhpTestFile
 *
 * Reads the test file and checks pre-conditions
 *
 * @category   Testing
 * @package    RUNTESTS
 * @author     Zoe Slattery <zoe@php.net>
 * @author     Stefan Priebsch <spriebsch@php.net>
 * @copyright  2009 The PHP Group
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 *
 */
class rtPhpTestFile
{
    private $fileName;
    private $testName;
    private $testContents;
    private $testExitMessage;
    private $sectionHeadings = array();

    private $carriageReturn = "\r";
    private $newLine = "\n";

    private $preConditions = array (
        'rtHasMandatorySections',
        'rtHasNoDuplicateSections',
        'rtIsValidSectionName',
        'rtIsSectionImplemented'
        );

        /**
         * Reads the contents of the test file and creates an array of the contents.
         *
         * @param string $testFile (file name)
         */
        public function doRead($testFile)
        {
            $this->testFileName = realpath($testFile);
            $this->testName= dirname($this->testFileName).DIRECTORY_SEPARATOR.basename($this->testFileName, ".phpt");
            $this->testContents = file($this->testFileName);
        }

        public function normaliseLineEndings()
        {
            $tempArray = array();
            for ($i=0; $i<count($this->testContents); $i++) {
                //This is not nice but there are a huge number of tests with random spacs at the end of the section header
                //and empty sections.
                if (preg_match("/^\s*--([A-Z]+(_[A-Z]+|))--/", $this->testContents[$i], $matches)) {
                    //look ahead to next section unless this is the last test section. 
                    //if the EXPECT section is empty (missing) it will be caught by preconditions.
                    //If the next line is also a section heading than skip adding it to the test case or headings.
                    if($i< count($this->testContents) - 1) {
                        if (!preg_match("/--([A-Z]+(_[A-Z]+|))--/", $this->testContents[$i+1])) {
                            $this->sectionHeadings[] = $matches[1];
                            $tempArray[] = $matches[1];
                        }
                    }
                } else {
                    $tempArray[] = rtrim($this->testContents[$i], $this->carriageReturn.$this->newLine);
                }
            }
            $this->testContents = $tempArray;
           
        }

        public function arePreConditionsMet()
        {
            foreach ($this->preConditions as $preCondition) {
                $condition = new $preCondition;               
                if (!$condition->isMet($this->sectionHeadings)) {
                    $this->testExitMessage = $condition->getMessage();
                    return false;
                }
            }
            return true;
        }

        public function getContents()
        {
            return $this->testContents;
        }

        public function getSectionHeadings()
        {
            return $this->sectionHeadings;
        }


        public function getTestName()
        {
            return $this->testName;
        }


        public function getTestFileName()
        {
            return $this->testFileName;
        }


        public function getExitMessage()
        {
            return $this->testExitMessage;
        }
}
?>
