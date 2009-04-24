<?php
/**
 * Class representing expected output and actual output
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
