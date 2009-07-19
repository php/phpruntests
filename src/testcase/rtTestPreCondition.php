<?php
/**
 * rtTestPreCondition
 *
 * Parent class for test case pre-conditions
 *
 * @category   Testing
 * @package    RUNTESTS
 * @author     Zoe Slattery <zoe@php.net>
 * @author     Stefan Priebsch <spriebsch@php.net>
 * @copyright  2009 The PHP Group
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 *
 */
interface rtTestPreCondition
{
    public function isMet(array $testContents, array $sectionHeaders);    

    public function getMessage(); 
}

?>
