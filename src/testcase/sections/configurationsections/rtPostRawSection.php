<?php
/**
 * rtPostSection
 * Sets environment variables for POST_RAW section
 *
 *
 * @category  Testing
 * @package   RUNTESTS
 * @author    Zoe Slattery <zoe@php.net>
 * @author    Stefan Priebsch <spriebsch@php.net>
 * @copyright 2009 The PHP Group
 * @license   http://www.php.net/license/3_01.txt PHP License 3.01
 * @link      http://qa.php.net/
 */
class rtPostRawSection extends rtConfigurationSection
{
    protected $postVariables = array();
    protected $postFileName;

    protected function init()
    {
        $postContents = array();
        $isContentSet= false;

        foreach($this->sectionContents as $line) {
            //get the first - and only the first - Content-Type line
            if (!$isContentSet && preg_match('/^Content-Type:(.*)/i', $line, $matches)) {
                $this->postVariables['CONTENT_TYPE'] = trim(str_replace("\r", '', $matches[1]));
                $isContentSet = true;
            } else {
                $postContents[] = $line;
            }
        }

    $postString = implode("\n", $postContents);
    $this->postVariables['CONTENT_LENGTH'] = strlen($postString);
    $this->postVariables['REQUEST_METHOD'] = 'POST';

    $this->postFileName = $this->testName . ".post";
   
    file_put_contents($this->postFileName, $postString);
}

/**
 * Additional POST environment variables required by the test
 *
 * @return array
 */
public function getPostVariables()
{
    return $this->postVariables;
}

/**
 * return the name of teh file containing post data
 *
 */
public function getPostFileName()
{
    return $this->postFileName;
}
}
?>