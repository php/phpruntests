<?php
/**
 * rtHasNoDuplicateSections
 *
 * Checks that the test case does not have duplicate sections
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
class rtHasNoDuplicateSections implements rtTestPreCondition
{
    /**
     * Return the message associated with a duplicate test section
     *
     * @return text
     */
    public function getMessage()
    {
        return rtText::get('duplicateTestSection');
    }

    /**
     * Check that the PHP executable is a valid executable
     *
     * @param array $testCaseContents, array $sectionHeaders
     * @return boolean
     */
    public function isMet(array $testContents, array $sectionHeaders)
    {
        $uniqueSections = array_unique($sectionHeaders);
        
        if (count($uniqueSections) < count($sectionHeaders)) {
            return false;
        }

        return true;
    }
}
?>
