<?php
class rtIsNotRedirected implements rtTestPreCondition
{
     
    /** Return the message associated with a redirected test section
     *
     * @return text
     */
    public function getMessage()
    {
        return rtText::get('isRedirected');
    }

    /**
     * Check whether there is a REDIRECT section
     *
     * @param array $testCaseContents, array $sectionHeaders
     * @return boolean
     */
    public function isMet(array $testContents, array $sectionHeaders)
    {
        foreach ($sectionHeaders as $section) {
        	
            if ($section == "REDIRECTTEST") {
                return false;
            }
        }
        return true;
    }
}
?>
