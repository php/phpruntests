<?php

  require_once 'PHP/CodeSniffer/Standards/CodingStandard.php';
  
  class PHP_CodeSniffer_Standards_formatting_formattingCodingStandard extends PHP_CodeSniffer_Standards_CodingStandard
  {
    public function getIncludedSniffs()
    {
      $result = array(

        'Generic/Sniffs/ControlStructures/InlineControlStructureSniff.php',

        'Generic/Sniffs/Files/LineEndingsSniff.php',
        'Generic/Sniffs/Files/LineLengthSniff.php',

        'Generic/Sniffs/Formatting/DisallowMultipleStatementsSniff.php',
        'Generic/Sniffs/Formatting/MultipleStatementAlignmentSniff.php',
        'Generic/Sniffs/Formatting/SpaceAfterCastSniff.php',

        'Generic/Sniffs/Functions/OpeningFunctionBraceBsdAllmanSniff.php', 

        'Generic/Sniffs/WhiteSpace/DisallowTabIndentSniff.php',

        'PEAR/Sniffs/Classes/ClassDeclarationSniff.php',

        'PEAR/Sniffs/ControlStructures/ControlSignatureSniff.php',
        'PEAR/Sniffs/Functions/FunctionCallArgumentSpacingSniff.php',
        'PEAR/Sniffs/Functions/FunctionCallSignatureSniff.php',
        'PEAR/Sniffs/WhiteSpace/ScopeClosingBraceSniff.php',

        // 'Squiz/Sniffs/Arrays/ArrayBracketSpacingSniff.php',
        // 'Squiz/Sniffs/Arrays/ArrayDeclarationSniff.php',
        'Squiz/Sniffs/Classes/ClassDeclarationSniff.php',
        'Squiz/Sniffs/Classes/SelfMemberReferenceSniff.php',
        'Squiz/Sniffs/ControlStructures/ElseIfDeclarationSniff.php',
        'Squiz/Sniffs/ControlStructures/ForEachLoopDeclarationSniff.php',
        'Squiz/Sniffs/ControlStructures/SwitchDeclarationSniff.php',
        'Squiz/Sniffs/Formatting/OperatorBracketSniff.php',
        'Squiz/Sniffs/Functions/FunctionDeclarationSniff.php',
        'Squiz/Sniffs/PHP/DisallowInlineIfSniff.php',
        'Squiz/Sniffs/PHP/DisallowMultipleAssignmentsSniff.php',
        'Squiz/Sniffs/Strings/EchoedStringsSniff.php',
        'Squiz/Sniffs/WhiteSpace/FunctionOpeningBraceSpaceSniff.php',
        'Squiz/Sniffs/WhiteSpace/FunctionSpacingSniff.php',
        'Squiz/Sniffs/WhiteSpace/LanguageConstructSpacingSniff.php',
        'Squiz/Sniffs/WhiteSpace/MemberVarSpacingSniff.php',
        'Squiz/Sniffs/WhiteSpace/ObjectOperatorSpacingSniff.php',
        'Squiz/Sniffs/WhiteSpace/OperatorSpacingSniff.php',
        'Squiz/Sniffs/WhiteSpace/ScopeKeywordSpacingSniff.php',
        'Squiz/Sniffs/WhiteSpace/SuperfluousWhitespaceSniff.php',
      );

      $custom = array (
        'Sniffs/ScopeIndentSniff.php',
      );

      foreach ($custom as $item)
      {
        $result[] = dirname(__FILE__) . '/' . $item;
      }

      return $result;
    }
  }

?>
