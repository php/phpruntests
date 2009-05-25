<?php
/**
 * rtExpectSection
 * 
 * Class for handling EXPECT sections
 *
 * @category  Testing
 * @package   RUNTESTS
 * @author    Zoe Slattery <zoe@php.net>
 * @author    Stefan Priebsch <spriebsch@php.net>
 * @copyright 2009 The PHP Group
 * @license   http://www.php.net/license/3_01.txt PHP License 3.01
 * @link      http://qa.php.net/
 */
class rtExpectSection extends rtOutputSection
{
    protected function init()
    {
        parent::createPattern();
    }

    /**
     * Compare the test output with the expected pattern
     *
     * @param string $testOutput
     * @return boolean
     */
    public function compare($testOutput)
    {
        $testOutput = trim(preg_replace("/$this->carriageReturnLineFeed/", $this->lineFeed, $testOutput));

        if (strcmp($this->expectedPattern, $testOutput) == 0) {
            return true;
        } else {
            return false;
        }
    }
}
?>
