<?php
/**
 * rtIsValidSEctionName
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
class rtIsValidSectionName implements rtTestPreCondition
{
	//NOTE: The sections REQUEST, CGI, HEADERS are implemented in server-tests.php only
    protected $validSectionNames = array(
        'TEST',
        'DESCRIPTION',
        'SKIPIF',
        'FILE',
        'FILEEOF', 
        'FILE_EXTERNAL',   
        'EXPECT',
        'EXPECTF',
        'EXPECTREGEX',
        'INI',
        'ARGS', 
        'ENV',
        'STDIN',
        'XFAIL', 
        'STDIN',
        'CREDITS',
        'CLEAN',
        'POST', 
        'GZIP_POST',
        'DEFLATE_POST',            
        'POST_RAW',
        'PUT',
        'GET',
        'COOKIE',
        'REDIRECTTEST', 
        'EXPECTHEADERS', 
    );

    /**
     * Return the message associated with an invalid test section
     *
     * @return text
     */
    public function getMessage()
    {
        return rtText::get('invalidTestSection');
    }

    /**
     * Check that the section name is valid
     *
     * @param array $testCaseContents, array $sectionHeaders
     * @return boolean
     */
    public function isMet(array $testContents, array $sectionHeaders)
    {
        foreach ($sectionHeaders as $section) {
            if (!in_array($section, $this->validSectionNames)) {
                return false;
            }
        }
        return true;
    }
}
?>
