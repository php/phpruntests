<?php

/**
 * Class containing the base set of pre-conditions that must be met whatever
 * platform run-tests is executed on
 *
 */
abstract class rtPreConditionList
{
    protected $preConditions = array (
        'rtIsExecutableSet',
        'rtIsPcreLoaded',
        'rtIsProcOpenAvailable',
        'rtIsSafeModeDisabled',
        'rtIsTestFileSpecified',
        'rtIsPhpVersionCorrect',
    );

    /**
     * Gets instance of a platform specific pre-condition list
     *
     * @param string $os
     * @return rtPreConditionList Platform specific pre-condition list
     */
    static public function getInstance($os = 'Unix')
    {
        if ($os == 'Windows') {
            return new rtWinPreConditionList();
        } else {
            return new rtUnixPreConditionList();
        }
    }

    /**
     * Checks the validity of each pre-condition in the list
     *
     * @param rtCommandLineOptions $commandLine
     * @param rtEnvironmentVariables $environmentVariables
     * @return boolean
     * @todo modify to check all preconditions, and return a list of failed ones
     */
    public function check(rtRuntestsConfiguration $config)
    {
        foreach ($this->preConditions as $preCon) {
            $p = new $preCon;
            
            if (!$p->check($config)) {
                throw new Exception($p->getMessage());
            }
        }

        return true;
    }
}
?>
