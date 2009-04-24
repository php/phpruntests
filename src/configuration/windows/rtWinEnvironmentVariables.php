<?php
/**
 * Class to adapt environment variable list to Windows
 *
 */
class rtWinEnvironmentVariables extends rtEnvironmentVariables
{    
    /**
     * Adapts the environment variables for Windows
     *
     */
    public function adaptEnvironment()
    {
        array_push($this->userSuppliedVariables, 'SystemRoot');
    }
}
?>
