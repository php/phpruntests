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
        
        private function isSectionHeading($string) {
            return preg_match("/^\s*--[A-Z]+(_[A-Z]+|)--/", $string);
        }
        
        private function getUntrimmedSectionHeading($string) {
            preg_match("/^\s*(--[A-Z]+(_[A-Z]+|)--)/", $string, $matches);
            return $matches[1];
        }
        
        private function getSectionHeading($string) {
            preg_match("/^\s*--([A-Z]+(_[A-Z]+|))--/", $string, $matches);
            return $matches[1];
        }
        

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

        /*
         * Trims the  lines endings - if the line is a section header it gets trimmed to either side of the --. 
         * This is to avoid problems with tests that have spurious characters after the header.
         */
        public function normaliseLineEndings()
        {
            for ($i=0; $i<count($this->testContents); $i++) {
                //Just trim the contents lines here not the section header lines
                if ($this->isSectionHeading($this->testContents[$i])) {
                    $this->testContents[$i] = $this->getUntrimmedSectionHeading($this->testContents[$i]);
                }else {
                    $this->testContents[$i] = rtrim($this->testContents[$i], $this->carriageReturn.$this->newLine);
                }
            }
        }
        
        /*
         * Removes and discards any empty test sections
         * Constructs a list of section headingg, stripped of their -- identifiers.
         */
        public function removeEmptySections() {
            $tempArray = array();
            
            for ($i=0; $i<count($this->testContents) - 1; $i++) {
                $nextLine = $this->testContents[$i+1];
                $thisLine = $this->testContents[$i];
                if ($this->isSectionHeading($thisLine)) {
                     if (!$this->isSectionHeading($nextLine)) {
                        $tempArray[] = $this->getUntrimmedSectionHeading($thisLine);
                        $this->sectionHeadings[] = $this->getSectionHeading($thisLine);
                    }
                } else {
                    $tempArray[] = $thisLine;
                }
            }
            
            if($this->isSectionHeading(end($this->testContents))) {
                $this->sectionHeadings[] = $this->getSectionHeading(end($this->testContents));
            }
            $tempArray[] = end($this->testContents);
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
