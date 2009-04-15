<?php

  require_once 'PHP/CodeSniffer/Standards/CodingStandard.php';
  
  class PHP_CodeSniffer_Standards_guidelines_guidelinesCodingStandard extends PHP_CodeSniffer_Standards_CodingStandard
  {
    public function getIncludedSniffs()
    {
      return array(
        'PEAR/Sniffs/Files/IncludingFileSniff.php',
        'PEAR/Sniffs/Functions/ValidDefaultValueSniff.php',
        'Squiz/Sniffs/Commenting/EmptyCatchCommentSniff.php',
        'Squiz/Sniffs/Functions/GlobalFunctionSniff.php',
        'Squiz/Sniffs/Operators/IncrementDecrementUsageSniff.php',
        'Squiz/Sniffs/Operators/ValidLogicalOperatorsSniff.php',
        'Squiz/Sniffs/PHP/DisallowSizeFunctionsInLoopsSniff.php',
        'Squiz/Sniffs/PHP/EvalSniff.php',
        'Squiz/Sniffs/PHP/GlobalKeywordSniff.php',
        'Squiz/Sniffs/PHP/InnerFunctionsSniff.php',
        'Squiz/Sniffs/PHP/NonExecutableCodeSniff.php',
        'Squiz/Sniffs/Scope/MemberVarScopeSniff.php',
        'Squiz/Sniffs/Scope/MethodScopeSniff.php',
        'Squiz/Sniffs/Scope/StaticThisUsageSniff.php',
      );
    }
  }

?>
