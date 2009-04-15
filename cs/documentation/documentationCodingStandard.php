<?php

  require_once 'PHP/CodeSniffer/Standards/CodingStandard.php';
  
  class PHP_CodeSniffer_Standards_documentation_documentationCodingStandard extends PHP_CodeSniffer_Standards_CodingStandard
  {
    public function getIncludedSniffs()
    {
      $result = array(

        'PEAR/Sniffs/Commenting/InlineCommentSniff.php',
        // Checks that no perl-style comments are used.

        'Squiz/Sniffs/Commenting/DocCommentAlignmentSniff.php', 
        // Tests that the stars in a doc comment align correctly.

        'Squiz/Sniffs/Commenting/FunctionCommentThrowTagSniff.php',
        // Verifies that a @throws tag exists for a function that throws exceptions.
        // Verifies the number of @throws tags and the number of throw tokens matches.
        // Verifies the exception type.

        'Squiz/Sniffs/Commenting/PostStatementCommentSniff.php', 
        // Checks to ensure that there are no comments after statements.
      );

      $custom = array (
        'Sniffs/FunctionCommentSniff.php',
        // Ensures that every method has a comment that looks correct.

        'Sniffs/ClassCommentSniff.php',
        // Ensures that every class has a comment that looks correct.

        'Sniffs/VariableCommentSniff.php',
        // Ensures that every variable has a command that looks correct.
      );

      foreach ($custom as $item)
      {
        $result[] = dirname(__FILE__) . '/' . $item;
      }

      return $result;
    }
  }

?>
