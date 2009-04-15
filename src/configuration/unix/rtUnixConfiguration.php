<?php

/**
 * Class to adapt global environment to Unix
 *
 */
  class rtUnixConfiguration extends rtRuntestsConfiguration
  {
    
  public function __construct($cmdlineArgs) {
    $this->commandLineArgs = $cmdlineArgs;
    $this->init();
  }
    
    /**
     * Adapts the configuration to Unix
     *
     */
    protected function adaptConfiguration()
    {
    }
  }

?>
