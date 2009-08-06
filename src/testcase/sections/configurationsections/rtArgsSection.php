<?php
/**
 * rtArgsSection
 *
 * Adds section content to the test command line arguments
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
class rtArgsSection extends rtConfigurationSection
{
    protected $testCommandLineArguments; 
    
    protected function init()
    {
        $this->testCommandLineArguments = '-- ' . $this->sectionContents[0];
    }
    
    /**
     * Return additional arguments to be added to the PHP Test command line
     *
     * @return string
     */
    public function getTestCommandLineArguments()
    {
        return $this->testCommandLineArguments;
    }
}
?>
