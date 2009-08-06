<?php
/**
 * rtEnvSection
 *
 * Adds section content to the test environment variables
 * 
 * @category   Testing
 * @package    RUNTESTS
 * @author     Zoe Slattery <zoe@php.net>
 * @author     Stefan Priebsch <spriebsch@php.net>
 * @copyright  2009 The PHP Group
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link       http://qa.php.net/
 * 
 */
class rtEnvSection extends rtConfigurationSection
{
    protected $testEnvironmentVariables = array();

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
