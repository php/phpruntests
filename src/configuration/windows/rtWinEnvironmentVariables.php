<?php
/**
 * rtWinEnvironmentVariables
 *
 * Windows specific environment variables
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
class rtWinEnvironmentVariables extends rtEnvironmentVariables
{    
    /**
     * Adapts the environment variables for Windows
     *
     */
    public function __construct()
    {
    	array_push($this->userSuppliedVariables, 'SystemRoot');
    }
}
?>
