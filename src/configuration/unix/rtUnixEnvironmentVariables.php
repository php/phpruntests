<?php
/**
 * rtUnixEnvironmentVariables
 *
 * Deals with any environment varibale that only apply to Unix
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
class rtUnixEnvironmentVariables extends rtEnvironmentVariables
{    
    /**
     * Adapts the environment to Unix
     *
     */
    public function __construct()
    {
        array_push($this->userSuppliedVariables, 'TEST_PHP_PARALLEL');
    }
}
?>
