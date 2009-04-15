<?php


class rtIsValidSectionName implements rtTestPreCondition {


  private $validSectionNames =  array (
                              'TEST',
                              'SKIPIF',
                              'FILE',
                              'FILEEOF', 
                              'FILE_EXTERNAL',   
                              'EXPECT',
                              'EXPECTF',
                              'EXPECTREGEX',
                              'INI',
                              'ARGS', 
                              'ENV',
                              'XFAIL', 
                              'STDIN',
                              'CREDITS',
                              'CLEAN',
                              'POST', 
                              'GZIP_POST',
                              'DEFLATE_POST',            
                              'POST_RAW',
                              'GET',
                              'COOKIE',
                              'REDIRECTTEST', 
                              'HEADERS',
                              'EXPECTHEADERS',   
                              '===DONE===',
  );

  /** Return the message associated with a duplicate test section
   *
   * @return text
   */
  public function getMessage() {
    return rtText::get('invalidTestSection');
  }


  /**
   * Check that the section name is valid
   *
   * @param array $testCaseContents
   * @return boolean
   */
  public function isMet(array $sectionHeadings) {
    foreach ($sectionHeadings as $section) {
      if(!in_array($section, $this->validSectionNames)) {
        return false;
      }
      return true;
    }
  }
}
  ?>