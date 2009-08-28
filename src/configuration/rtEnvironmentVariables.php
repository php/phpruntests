<?php
/**
 * rtEnvironmentVariables
 *
 * Class to retrieve the values of environmental variables and 
 * store them.
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
class rtEnvironmentVariables
{
    protected $userSuppliedVariables = array (
        'TEST_PHP_EXECUTABLE',
        'TEST_PHP_SRCDIR',
        'TEST_PHP_CGI_EXECUTABLE',
        'TEST_PHP_LOG_FORMAT',
        'TEST_PHP_DETAILED',
        'TEST_PHP_USER',
        'NO_INTERACTION',
        'PHP_AUTOCONF',
        'TEST_PHP_ARGS',
        'REPORT_EXIT_STATUS',
        'TEST_PHP_ERROR_STYLE',
        'NO_PHPTEST_SUMMARY',
    );

    protected $environmentVariables = array();

    
    protected function __construct() {}

    
    static public function getInstance ($os = 'Unix')
    {
        if ($os == 'Windows') {
            return new rtWinEnvironmentVariables();
        } else {
            return new rtUnixEnvironmentVariables();
        }
    }

    public function getUserSuppliedVariables()
    {
        if (isset($_ENV)) {
            $this->environmentVariables = $_ENV;
        } 

        foreach ($this->userSuppliedVariables as $variable) {
            if (getenv($variable)) {
                $this->environmentVariables[$variable] = getenv($variable);
            }
        }
    }

    public function setVariable($var,$value)
    {
        $this->environmentVariables[$var] = $value;
    }

    public function getVariable($var)
    {
        return $this->environmentVariables[$var];
    }

    public function hasVariable($var)
    {
        return (isset ($this->environmentVariables[$var])); 
    }
    
    public function getVariables()
    {
        return $this->environmentVariables;
    }
}
?>
