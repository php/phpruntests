<?php
/**
 * rtAddToCommandLine
 *
 * Command line arguments can be supplied by an env variable. 
 * This class ensures that additional arguments are parsed
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
class rtAddToCommandLine
{
    /**
     * Parse additional values if supplied by env variable
     *
     * @param rtCommandLineOptions $commandLine
     * @param rtEnvironmentVariables $environmentVariables
     */
    public static function parseAdditionalOptions($commandLine, $environmentVariables)
    {
        if ($environmentVariables->hasVariable('TEST_PHP_ARGS')) {
            $additionalOptions = array_merge(array('run-test.php'), explode(" ", $environmentVariables->getVariable('TEST_PHP_ARGS')));
            $commandLine->parse($additionalOptions);
        }
    }  
}
?>
