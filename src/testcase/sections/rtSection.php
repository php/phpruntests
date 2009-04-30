<?php
/**
 * Parent class for all test case sections
 *
 */
abstract class rtSection
{
    private static $sectionMap = array (
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
        'GZIP_POST'   => 'rtGzipPostSection',
        'DEFLATE_POST'  => 'rtDeflatePostSection',
    );

    protected $sectionName;
    protected $sectionContents;

    protected $carriageReturnLineFeed = "\r\n";
    protected $lineFeed = "\n";

    public function __construct($sectionName, $contents)
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
