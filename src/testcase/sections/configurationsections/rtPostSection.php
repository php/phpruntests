<?php
/**
 * rtPostSection
 * Sets environment variables for POST section
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
class rtPostSection extends rtConfigurationSection
{
    protected $postVariables = array();
    protected $postFileName;

    protected function init()
    { 
        $postString = implode("\n", $this->sectionContents);
        $this->postVariables['CONTENT_TYPE'] = 'application/x-www-form-urlencoded';
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