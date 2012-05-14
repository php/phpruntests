#!/bin/sh
# Use this to create the QA test bucket. eq $2=/tmp/blah, $1=/path/to/php/source
mkdir -p $2/ext/ctype/tests 
cp -r $1/php-5.4.0/ext/ctype/tests/* $2/ext/ctype/tests
mkdir -p $2/ext/date/tests 
cp -r $1/php-5.4.0/ext/date/tests/* $2/ext/date/tests
mkdir -p $2/ext/dom/tests 
cp -r $1/php-5.4.0/ext/dom/tests/* $2/ext/dom/tests
mkdir -p $2/ext/ereg/tests 
cp -r $1/php-5.4.0/ext/ereg/tests/* $2/ext/ereg/tests
mkdir -p $2/ext/fileinfo/tests 
cp -r $1/php-5.4.0/ext/fileinfo/tests/* $2/ext/fileinfo/tests
mkdir -p $2/ext/filter/tests 
cp -r $1/php-5.4.0/ext/filter/tests/* $2/ext/filter/tests
mkdir -p $2/ext/iconv/tests 
cp -r $1/php-5.4.0/ext/iconv/tests/* $2/ext/iconv/tests
mkdir -p $2/ext/json/tests 
cp -r $1/php-5.4.0/ext/json/tests/* $2/ext/json/tests
mkdir -p $2/ext/libxml/tests 
cp -r $1/php-5.4.0/ext/libxml/tests/* $2/ext/libxml/tests
mkdir -p $2/ext/mysql/tests 
cp -r $1/php-5.4.0/ext/mysql/tests/* $2/ext/mysql/tests
mkdir -p $2/ext/mysqli/tests 
cp -r $1/php-5.4.0/ext/mysqli/tests/* $2/ext/mysqli/tests
mkdir -p $2/ext/pcre/tests 
cp -r $1/php-5.4.0/ext/pcre/tests/* $2/ext/pcre/tests
mkdir -p $2/ext/pdo/tests 
cp -r $1/php-5.4.0/ext/pdo/tests/* $2/ext/pdo/tests
mkdir -p $2/ext/pdo_mysql/tests 
cp -r $1/php-5.4.0/ext/pdo_mysql/tests/* $2/ext/pdo_mysql/tests
mkdir -p $2/ext/pdo_sqlite/tests 
cp -r $1/php-5.4.0/ext/pdo_sqlite/tests/* $2/ext/pdo_sqlite/tests
mkdir -p $2/ext/phar/tests 
cp -r $1/php-5.4.0/ext/phar/tests/* $2/ext/phar/tests
mkdir -p $2/ext/posix/tests 
cp -r $1/php-5.4.0/ext/posix/tests/* $2/ext/posix/tests
mkdir -p $2/ext/reflection/tests 
cp -r $1/php-5.4.0/ext/reflection/tests/* $2/ext/reflection/tests
mkdir -p $2/ext/session/tests 
cp -r $1/php-5.4.0/ext/session/tests/* $2/ext/session/tests
mkdir -p $2/ext/spl/examples/tests 
cp -r $1/php-5.4.0/ext/spl/examples/tests/* $2/ext/spl/examples/tests
mkdir -p $2/ext/spl/tests 
cp -r $1/php-5.4.0/ext/spl/tests/* $2/ext/spl/tests
mkdir -p $2/ext/sqlite3/tests 
cp -r $1/php-5.4.0/ext/sqlite3/tests/* $2/ext/sqlite3/tests
mkdir -p $2/ext/standard/tests 
cp -r $1/php-5.4.0/ext/standard/tests/* $2/ext/standard/tests
mkdir -p $2/ext/tokenizer/tests 
cp -r $1/php-5.4.0/ext/tokenizer/tests/* $2/ext/tokenizer/tests
mkdir -p $2/ext/xml/tests 
cp -r $1/php-5.4.0/ext/xml/tests/* $2/ext/xml/tests
mkdir -p $2/ext/xmlreader/tests 
cp -r $1/php-5.4.0/ext/xmlreader/tests/* $2/ext/xmlreader/tests
mkdir -p $2/ext/xmlwriter/tests 
cp -r $1/php-5.4.0/ext/xmlwriter/tests/* $2/ext/xmlwriter/tests
mkdir -p $2/ext/zlib/tests 
cp -r $1/php-5.4.0/ext/zlib/tests/* $2/ext/zlib/tests
mkdir -p $2/sapi/cgi/tests 
cp -r $1/php-5.4.0/sapi/cgi/tests/* $2/sapi/cgi/tests
mkdir -p $2/sapi/cli/tests 
cp -r $1/php-5.4.0/sapi/cli/tests/* $2/sapi/cli/tests
mkdir -p $2/sapi/tests 
cp -r $1/php-5.4.0/sapi/tests/* $2/sapi/tests
mkdir -p $2/tests 
cp -r $1/php-5.4.0/tests/* $2/tests
mkdir -p $2/Zend/tests 
cp -r $1/php-5.4.0/Zend/tests/* $2/Zend/tests
#extract these tests as they don't run outside the PHP source tree
rm $2/ext/dom/tests/DOMDocument_validate_on_parse_variation.phpt
rm $2/ext/spl/examples/tests/dualiterator_001.phpt
rm $2/ext/standard/tests/file/006_variation2.phpt
rm $2/ext/standard/tests/file/chmod_basic.phpt
rm $2/ext/zlib/tests/compress_zlib_wrapper.phpt
