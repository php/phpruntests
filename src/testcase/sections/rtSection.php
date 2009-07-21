<?php
/**
 * rtSection
 * 
 * Parent class for all test sections
 *
 *
 * @category  Testing
 * @package   RUNTESTS
 * @author    Zoe Slattery <zoe@php.net>
 * @author    Stefan Priebsch <spriebsch@php.net>
 * @copyright 2009 The PHP Group
 * @license   http://www.php.net/license/3_01.txt PHP License 3.01
 * @link      http://qa.php.net/
 */
abstract class rtSection
{
    private static $sectionMap = array (
        'TEST'        => 'rtTestHeaderSection',
        'DESCRIPTION' => 'rtDescriptionSection',
        'SKIPIF'      => 'rtSkipIfSection',
        'FILE'        => 'rtFileSection',
        'EXPECT'      => 'rtExpectSection',
        'EXPECTF'     => 'rtExpectFSection',
        'EXPECTREGEX' => 'rtExpectRegexSection',
        'INI'         => 'rtIniSection',
        'ARGS'        => 'rtArgsSection',
        'ENV'         => 'rtEnvSection',
        'STDIN'       => 'rtStdinSection',
        'CREDITS'     => 'rtCreditsSection',
        'CLEAN'       => 'rtCleanSection',
        'XFAIL'       => 'rtXfailSection',
        'GET'         => 'rtGetSection',
        'POST'        => 'rtPostSection',
        'GZIP_POST'   => 'rtGzipPostSection',
        'DEFLATE_POST'  => 'rtDeflatePostSection',
        'POST_RAW'  => 'rtPostRawSection',
        'COOKIE'    => 'rtCookieSection',
        'FILE_EXTERNAL' =>  'rtFileExternalSection',
        'EXPECTHEADERS' => 'rtExpectHeadersSection',
    );

    protected $sectionName;
    protected $sectionContents;

    protected $carriageReturnLineFeed = "\r\n";
    protected $lineFeed = "\n";

    protected function __construct($sectionName, $contents)
    {
        $this->sectionName = $sectionName;
        $this->sectionContents = $contents;
        $this->init();
    }

    abstract protected function init();

    public static function getInstance($sectionName, $contents)
    {
        if (!isset(self::$sectionMap[$sectionName])) {
            throw new RuntimeException('Unknown section type ' . $sectionName);
        }

        return new rtSection::$sectionMap[$sectionName]($sectionName, $contents);
    }

    public function getName()
    {
        return $this->sectionName;
    }

    public function getContents()
    {
        return $this->sectionContents;
    }
}
?>
