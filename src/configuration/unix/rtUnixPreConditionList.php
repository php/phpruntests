<?php
/**
 * rtUnixPreConditionList
 *
 * List of pre-conditions specific to Unix
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
class rtUnixPreConditionList extends rtPreconditionList
{
    /**
     * Adapts the list of pre-conditions to include those that relate to Unix only
     *
     */
    public function adaptList()
    {
        array_push($this->preConditions, 'rtIfParallelHasPcntl');
    }
}
?>
