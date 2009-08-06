<?php
/**
 * rtExpectFSection
 *
 * Class for handling EXPECTF sections
 *
 * @category  Testing
 * @package   RUNTESTS
 * @author    Zoe Slattery <zoe@php.net>
 * @author    Stefan Priebsch <spriebsch@php.net>
 * @copyright 2009 The PHP Group
 * @license   http://www.php.net/license/3_01.txt PHP License 3.01
 * @link      http://qa.php.net/
 */
class rtExpectFSection extends rtOutputSection
{
    protected function init()
    {
        parent::createPattern();
        $this->createPattern();
    }
     
    /**
     * Create the pattern used to match against actual output
     *
     */
    protected function createPattern()
    {
         
        $this->expectedPattern = $this->expectfEmbeddedRegex($this->expectedPattern);
        $this->expectedPattern = $this->expectfUnicodeSubstitutions ($this->expectedPattern);
        $this->expectedPattern = $this->expectfRegexSubstitutions($this->expectedPattern);
    }

    /*
     * Replaces string with unicode and vice versa. Allows same tests for PHP5 and PHP6
     * @param string
     * @return string
     */
    protected function expectfUnicodeSubstitutions($string)
    {
        $string = str_replace(
        array('%unicode_string_optional%'),
        version_compare(PHP_VERSION, '6.0.0-dev') == -1 ? 'string' : 'Unicode string', $string
        );

        $string = str_replace(
        array('%unicode\|string%', '%string\|unicode%'),
        version_compare(PHP_VERSION, '6.0.0-dev') == -1 ? 'string' : 'unicode',
        $string
        );

        $string = str_replace(
        array('%u\|b%', '%b\|u%'),
        version_compare(PHP_VERSION, '6.0.0-dev') == -1 ? '' : 'u',
        $string
        );

        $string = str_replace(
        array('%binary_string_optional%'),
        version_compare(PHP_VERSION, '6.0.0-dev') == -1 ? 'string' : 'binary string',
        $string
        );



        return $string;
    }

    /*
     * Substitute the %strings used in EXPECTF sections with a regular expression
     * @param string
     * @return string
     */
    protected function expectfRegexSubstitutions($string)
    {
        $string = str_replace('%e', '\\' . DIRECTORY_SEPARATOR, $string);
        $string = str_replace('%s', '[^\r\n]+', $string);
        $string = str_replace('%S', '[^\r\n]*', $string);
        $string = str_replace('%a', '.+', $string);
        $string = str_replace('%A', '.*', $string);
        $string = str_replace('%w', '\s*', $string);
        $string = str_replace('%i', '[+-]?\d+', $string);
        $string = str_replace('%d', '\d+', $string);
        $string = str_replace('%x', '[0-9a-fA-F]+', $string);
        $string = str_replace('%f', '[+-]?\.?\d+\.?\d*(?:[Ee][+-]?\d+)?', $string);
        $string = str_replace('%c', '.', $string);

        return $string;
    }

    /*
     * Deal with embedded regular expressions between %r tags.
     * @param string
     * @return string
     */
    protected function expectfEmbeddedRegex($string) {
        $temp = "";
        $r = "%r";
        $startOffset = 0;

        $length = strlen($string);
        while($startOffset < $length) {
            $start = strpos($string, $r, $startOffset);
            if ($start !== false) {
                // we have found a start tag
                $end = strpos($string, $r, $start+2);
                if ($end === false) {
                    // unbalanced tag, ignore it.
                    $end = $start = $length;
                }
            } else {
                // no more %r sections
                $start = $end = $length;
            }
            // quote a non re portion of the string
            $temp = $temp . preg_quote(substr($string, $startOffset, ($start - $startOffset)),  '/');
            // add the re unquoted.
            $temp = $temp . '(' .substr($string, $start+2, ($end - $start-2)) . ')';
            $startOffset = $end + 2;
        }
        return substr($temp, 0, -2);
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

        /*For debugging:

        file_put_contents(sys_get_temp_dir().'/zrtexp',$this->expectedPattern );
        file_put_contents(sys_get_temp_dir().'/zrtout', $testOutput );

        */

        if (preg_match((binary) "/^$this->expectedPattern\$/s", $testOutput)) {
            return true;
        } else {
            return false;
        }
    }
}
?>
