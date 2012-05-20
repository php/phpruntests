<?php
/**
 * rtIsSectionImplemented
 *
 * Checks whether this version of run tests has implemented a section
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
class rtIsSectionImplemented implements rtTestPreCondition
{
    protected $sectionsNotImplementedMap = array(
        'PUT'        => 'rtPutSection',
        'FILEEOF' => 'rtDescriptionSection',
    );    

    /** Return the message associated with an unimplemented test section
     *
     * @return text
     */
    public function getMessage()
    {
        return rtText::get('sectionNotImplemented');
    }

    /**
     * Check that the section has been implemented
     *
     * @param array $testCaseContents, array $sectionHeaders
     * @return boolean
     */
    public function isMet(array $testContents, array $sectionHeaders)
    {
        foreach ($sectionHeaders as $section) {
            if (array_key_exists($section, $this->sectionsNotImplementedMap)) {
                return false;
            }
        }
        return true;
    }
}
?>
