<?php

  require_once 'PHP/CodeSniffer/Standards/CodingStandard.php';
  
  class PHP_CodeSniffer_Standards_compatibility_compatibilityCodingStandard extends PHP_CodeSniffer_Standards_CodingStandard
  {
    public function getIncludedSniffs()
    {
      return array(
        'Generic/Sniffs/PHP/DisallowShortOpenTagSniff.php',
        'Generic/Sniffs/PHP/ForbiddenFunctionsSniff.php',
      );
    }
  }

?>
