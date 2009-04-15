<?php

  require_once 'PHP/CodeSniffer/Standards/CodingStandard.php';
  
  class PHP_CodeSniffer_Standards_naming_namingCodingStandard extends PHP_CodeSniffer_Standards_CodingStandard
  {
    public function getIncludedSniffs()
    {
      return array(
        'Generic/Sniffs/NamingConventions/UpperCaseConstantNameSniff.php',
        'Generic/Sniffs/PHP/LowerCaseConstantSniff.php',

        'Squiz/Sniffs/Classes/LowercaseClassKeywordsSniff.php',
        'Squiz/Sniffs/ControlStructures/LowercaseDeclarationSniff.php',
        'Squiz/Sniffs/Functions/LowercaseFunctionKeywordsSniff.php',
        'Squiz/Sniffs/PHP/LowercasePHPFunctionsSniff.php',
      );
    }
  }

?>
