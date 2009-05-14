<?php
/**
 * rtExpectHeadersSection
 * Sets environment variables for EXPECT_HEADERS section
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
/**
 * Class representing expected output and actual headers
 */
class rtExpectHeadersSection extends rtOutputSection
{
    protected $expectedHeaders = array();
    
    protected function init()
    {
        $this->createPattern();
    }

    /**
     * Compare the headers with the expected headers
     *
     * @param string $testOutput
     * @return boolean
     */
    public function compare($testHeaders)
    {
        $testHeaders = explode($this->lineFeed, $testHeaders); 
        
        foreach ($testHeaders as $line) {
         if (strpos($line, ':') !== false) {
                list($headerKey, $headerValue) = explode(':', $line, 2);
                $this->outputHeaders[trim($headerKey)] = trim($headerValue);
            }
        }            

        foreach($this->expectedPattern as $headerKey => $headerValue) {
            if (!isset($this->outputHeaders[$headerKey]) || $this->outputHeaders[$headerKey] != $headerValue) {
                return false;
            }
        }
        return true;
    }

    /**
     * Note: For this class expectedPattern is an array not a string
     */
    protected function createPattern() {
        foreach($this->sectionContents as $line) {
            if (strpos($line, ':') !== false) {
                list($headerKey, $headerValue) = explode(':', $line, 2);
                $this->expectedPattern[trim($headerKey)] = trim($headerValue);
            }
        }
    }
}
?>
