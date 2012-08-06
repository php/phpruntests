--TEST--
Make sure tests skips OK
--SKIPIF--
<?php # vim:ft=php
 print 'skip'; ?>
--REDIRECTTEST--
return array(
    'ENV' => array(
            'PDOTEST_DSN' => 'sqlite::memory:'
        ),
    'TESTS' => 'ext/pdo/tests'
    );

