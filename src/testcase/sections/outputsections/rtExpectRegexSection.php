<?php
/**
 * Class representing expected output and actual output
 */
class rtExpectRegexSection extends rtOutputSection
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
