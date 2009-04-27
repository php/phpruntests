<?php
/**
 * rtPostSection
 * Sets environment variables for GET section
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
    private $postVariables = array();
    private $postFileName;

    protected function init()
    {
        $this->postVariables['CONTENT_TYPE'] = 'application/x-www-form-urlencoded';
        $this->postVariables['REQUEST_METHOD'] = 'POST';

        $postString = implode('\n', $this->sectionContents);
        $this->postFileName = tempnam(sys_get_temp_dir(), 'post');
        file_put_contents($this->postFileName, $postString);
    }

    /**
     * Additional GET environment variables required by the test
     *
     * @return array
     */
    public function getPostVariables()
    {
        return $this->postVariables;
    }

    public function getPostFileName()
    {
        return $this->postFileName;
    }
}
?>