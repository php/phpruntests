<?php
/**
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
    public function isMet(array $sectionHeadings)
    {

        if (in_array('TEST', $sectionHeadings )) {
            if (in_array('FILE', $sectionHeadings ) || in_array('FILEOF', $sectionHeadings) || in_array('FILE_EXTERNAL', $sectionHeadings)) {
                if (in_array('EXPECT', $sectionHeadings ) || in_array('EXPECTF', $sectionHeadings) || in_array('EXPECTREGEX', $sectionHeadings)) {
                    return true;
                }
            }
        }

        return false;
    } 
}
?>
