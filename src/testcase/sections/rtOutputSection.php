<?php
/**
 * rtOutputSection
 * 
 * Parent class for test output (ie EXPECT...) sections
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
abstract class rtOutputSection extends rtSection
{
    protected $expectedPattern;
    
    protected function init()
    {
        $this->createPattern();
    }
    
    /**
     * Create the pattern used to match against actual output
     *
     */
    protected function createPattern()
    {
        $this->expectedPattern = implode($this->lineFeed, $this->sectionContents);
        $this->expectedPattern = str_replace($this->carriageReturnLineFeed, $this->lineFeed, $this->expectedPattern);

        //remove any blank lines from the start and end
        $this->expectedPattern = trim($this->expectedPattern);
    }

    public function getPattern()
    {
        return $this->expectedPattern;
    }

    abstract function compare($testOutput);
}
?>
