<?php

/**
 * Class to adapt global environment to Windows
 *
 */
  class rtWinConfiguration extends rtRuntestsConfiguration
  {
  public function __construct($cmdlineArgs) {
    $this->commandLineArgs = $cmdlineArgs;
    $this->init();
  }
    /**
     * Adapt the environmnet to Windows
     *
     */
    protected function adaptConfiguration()
    {
    }
  }

?>
