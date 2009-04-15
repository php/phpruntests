<?php

/**
 * Class to adapt global environment to Unix
 *
 */
  class rtUnixEnvironmentVariables extends rtEnvironmentVariables
  {
    
    /**
     * Adapts the global environment to Unix
     *
     */
    public function adaptEnvironment()
    {
      array_push($this->userSuppliedVariables, 'TEST_PHP_PARALLEL');
    }
  }

?>