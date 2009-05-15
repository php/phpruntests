<?php
/**
 *
 */
class rtIsSectionImplemented implements rtTestPreCondition
{
    private $sectionMap = array(
        'TEST'        => 'rtTestHeaderSection',
        'SKIPIF'      => 'rtSkipIfSection',
        'FILE'        => 'rtFileSection',
        'EXPECT'      => 'rtExpectSection',
        'EXPECTF'     => 'rtExpectFSection',
        'EXPECTREGEX' => 'rtExpectRegexSection',
        'INI'         => 'rtIniSection',
        'ARGS'        => 'rtArgsSection',
        'ENV'         => 'rtEnvSection',
        'CREDITS'     => 'rtCreditsSection',
        'CLEAN'       => 'rtCleanSection',
        'XFAIL'       => 'rtXfailSection',
        'GET'         => 'rtGetSection',
        'POST'        => 'rtPostSection',
        'GZIP_POST'       => 'rtGzipPostSection',
        'DEFLATE_POST'       => 'rtDeflatePostSection',
        'POST_RAW'    => 'rtPostRawSection',
        'COOKIE'    => 'rtCookieSection',
        'FILE_EXTERNAL' =>  'rtFileExternalSection',
        'EXPECTHEADERS' => 'rtExpectHeadersSection',
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
     * @param array $testCaseContents
     * @return boolean
     */
    public function isMet(array $sectionHeaders)
    {
        foreach ($sectionHeaders as $section) {
            if (!array_key_exists($section, $this->sectionMap)) {
                return false;
            }
        }
        return true;
    }
}
?>
