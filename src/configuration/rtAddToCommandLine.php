<?php

/**
 * Command line arguments can be supplied by an env variable. This class ensures 
 * that additional arguments are parsed
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
