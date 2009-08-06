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
    protected $commandLineArguments = array();

    public function init()
    {
    }

    /*
     * There is an option to make the working directory the same as teh test'd directory using the INI options.
     * See for example tests/lang/bug32924.phpt.
     * This is a plain stupid way to do it. It should go as soon as there is an implementation of a SETUP  section
     */
    public function substitutePWD ($fileName) {
        $tempArray = array();
        foreach ($this->sectionContents as $line) {
            if (strpos($line, '{PWD}') !== false) {
                $tempArray[] = str_replace('{PWD}', dirname($fileName), $line);
            } else {
                $tempArray[] = $line;
            }

        }
        
        $this->sectionContents = $tempArray;
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
