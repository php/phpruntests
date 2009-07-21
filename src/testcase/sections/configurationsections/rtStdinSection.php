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
    private $inputString;
    
    protected function init()
    {
        $this->inputString = join($this->sectionContents, PHP_EOL) . PHP_EOL;
    } 

    public function getInputString() {
        return $this->inputString;
    }
   
}
?>
