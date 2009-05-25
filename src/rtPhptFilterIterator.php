<?php
/**
 * rtPhpFilterIterator
 *
 * Filter for .phpt file types
 * 
 * @category   Testing
 * @package    RUNTESTS
 * @author     Zoe Slattery <zoe@php.net>
 * @author     Stefan Priebsch <spriebsch@php.net>
 * @copyright  2009 The PHP Group
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 *
 */
class rtPhptFilterIterator extends FilterIterator
{
    public function accept()
    {
        return substr($this->current(), -strlen('.phpt')) == '.phpt';
    }
}
?>
