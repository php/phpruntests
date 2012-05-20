<?php
/**
 * rtHasMandatorySections
 *
 * Checks that the test case has mandatory sections
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
class rtHasMandatorySections implements rtTestPreCondition
{
    /**
     * Return the message associated with a missing test section
     *
     * @return text
     */
    public function getMessage()
    {
        return rtText::get('missingTestSection');
    }

    /**
     * Check that the test has valid sections
     *
     * @param array $testCaseContents
     * @return boolean
     */
    public function isMet(array $testContents, array $sectionHeaders)
    {

        if (in_array('TEST', $sectionHeaders )) {
        	
            if (in_array('FILE', $sectionHeaders ) || in_array('FILEOF', $sectionHeaders) || in_array('FILE_EXTERNAL', $sectionHeaders)) {
                if (in_array('EXPECT', $sectionHeaders ) || in_array('EXPECTF', $sectionHeaders) || in_array('EXPECTREGEX', $sectionHeaders)) {
                    return true;
                }
            } else {
            	if(in_array('REDIRECTTEST', $sectionHeaders)) {
            		return true;
            	}
            }
        }

        return false;
    } 
}
?>
