<?php
/**
 *
 */
class rtEnvSection extends rtConfigurationSection
{
    private $testEnvironmentVariables = array();

    protected function init()
    {
        foreach ($this->sectionContents as $line) {
            $firstEqualsPosition = strpos($line, "=");
            $variableName = substr($line, 0, -(strlen($line) - $firstEqualsPosition));
            $variableValue = substr($line, -(strlen($line) - $firstEqualsPosition - 1));
      
            $this->testEnvironmentVariables[trim($variableName)] = trim($variableValue);
        }
    }

    /**
     * Additional environment variables required by the test
     *
     * @return array
     */
    public function getTestEnvironmentVariables() 
    {
        return $this->testEnvironmentVariables;
    }
}
?>
