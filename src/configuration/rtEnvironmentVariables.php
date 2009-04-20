<?php

/**
 * Class to retrieve the values of environmnetal variables and store them
 * Needs some changes - don't document till complete
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

    private $environmentVariables;

    public function __construct()
    {
    }

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
        } else {
            $this->environmentVariables = array();
        }

        foreach($this->userSuppliedVariables as $variable) {
            if(getenv($variable)) {
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
