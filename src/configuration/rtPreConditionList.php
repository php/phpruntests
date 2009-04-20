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
            return new rtWinPreconditionList('Windows');
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
     */
    public function check($commandLine, $environmentVariables) {
        foreach($this->preConditions as $preCon) {
            $p = new $preCon;
            if(!$p->check($commandLine, $environmentVariables)) {
                die($p->getMessage());
            }
        }

        return true;
    }

    /**
     * Test to ensure that a particular pre-condition exists (used in testing)
     *
     * @param string $pc
     * @return boolean
     */
    public function hasPreCondition($pc)
    {
        if(in_array($pc, $this->preConditions)) {
            return true;
        }

        return false;
    }
}
?>
