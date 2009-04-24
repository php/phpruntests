<?php
/**
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
     * @param array $testCaseContents
     * @return boolean
     */
    public function isMet(array $sectionHeadings)
    {
        $uniqueSections = array_unique($sectionHeadings);
        
        if (count($uniqueSections) < count($sectionHeadings)) {
            return false;
        }

        return true;
    }
}
?>
