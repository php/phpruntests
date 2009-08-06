<?php
/**
 * rtStdinSection
 *
 * Make section content stdin for the test case
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
class rtStdinSection extends rtConfigurationSection
{   
    protected $inputString;
    
    protected function init()
    {
        //This really does need to be \n not PHPEOL.
        $this->inputString = join($this->sectionContents, "\n") . "\n";
    } 

    public function getInputString() {
        return $this->inputString;
    }
   
}
?>
