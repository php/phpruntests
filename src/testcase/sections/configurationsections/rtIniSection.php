<?php
/**
 * rtIniSection
 *
 * Adds section content to the test ini settings
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
class rtIniSection extends rtConfigurationSection
{
    private $commandLineArguments = array();

    public function init()
    {
        foreach ($this->sectionContents as $line) {
            $this->commandLineArguments[] = addslashes($line);
        }
    }
    
    /**
     * Returns any additional PHP commandline arguments
     *
     * @return array
     */
    public function getCommandLineArguments()
    {
        return $this->commandLineArguments;
    }
}
?>
