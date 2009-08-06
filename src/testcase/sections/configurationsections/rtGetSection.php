<?php
/**
 * rtGetSection
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
class rtGetSection extends rtConfigurationSection
{
    protected $getVariables = array();

    protected function init()
    {
        $this->getVariables['QUERY_STRING'] = $this->sectionContents[0];
        $this->getVariables['REQUEST_METHOD'] = 'GET';
    }

    /**
     * Additional GET environment variables required by the test
     *
     * @return array
     */
    public function getGetVariables()
    {
        return $this->getVariables;
    }
}
?>