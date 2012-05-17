<?php
/**
 * rtHasNoEmptySections
 *
 * Checks that a section name is valid
 *
 * @category   Testing
 * @package    RUNTESTS
 * @author     Zoe Slattery <zoe@php.net>
 * @author     Stefan Priebsch <spriebsch@php.net>
 * @copyright  2009 The PHP Group
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link       http://qa.php.net/
 *
 */
class rtHasNoEmptySections implements rtTestPreCondition
{
    protected function isSectionHeading($string) {
        return preg_match("/^--[A-Z]+(_[A-Z]+|)--/", $string);
    }

    /**
     * Return the message associated with an empty section
     *
     * @return text
     */
    public function getMessage()
    {
        return rtText::get('emptySection');
    }

    /**
     * Check that there is no empty section
     *
     * @param array $testCaseContents, array $sectionHeadings
     * @return boolean
     */
    public function isMet(array $testContents, array $sectionHeaders)
    {
        //TODO: This won't catch a section full of blank lines.
        //It prabably should :-(
        for ($i=0; $i<count($testContents) - 1; $i++) {
            $nextLine = $testContents[$i+1];
            $thisLine = $testContents[$i];
            if ($this->isSectionHeading($thisLine)) {
                if ($this->isSectionHeading($nextLine)) {
                    return false;
                }
            }
        }
        
        //This doesn't catch an empty EXPECT section at the end of the test
        //This is deliberate. It's reasonable to have an empty EXPECT section while developing the test
       
        return true;
    }
}
?>
