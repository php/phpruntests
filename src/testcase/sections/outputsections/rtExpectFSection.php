<?php


/**
 * Class representing expected and actual output from a test
 */
class rtExpectFSection extends rtOutputSection {
  
  protected function init() {
    parent::createPattern();
    $this->createPattern();
  }
 
  /**
   * Create the pattern used to match against actual output
   *
   */
  protected function createPattern() {    
    $this->expectedPattern = preg_quote($this->expectedPattern, '/'); 
    $this->expectedPattern = $this->expectfUnicodeSubstitutions ($this->expectedPattern);   
    $this->expectedPattern = $this->expectfRegexSubstitutions($this->expectedPattern);  
  }
  
 
  /*
   * Replaces string with unicode and vice versa. Allows same tests for PHP5 and PHP6
   * @param string
   * @return string
   */
  private function expectfUnicodeSubstitutions($string) {
    $string = str_replace(
    array('%unicode_string_optional%'),
    version_compare(PHP_VERSION, '6.0.0-dev') == -1 ? 'string' : 'Unicode string', $string );
    
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
    return $string;
  }

  
  /*
   * Substitute the %strings used in EXPECTF sections with a regular expression
   * @param string
   * @return string
   */
  private function expectfRegexSubstitutions($string) {

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
  
  
 
  /**
   * Compare the test output with the expected pattern
   *
   * @param string $testOutput
   * @return boolean
   */
  public function compare($testOutput) {
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