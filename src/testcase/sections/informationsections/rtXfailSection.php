<?php
/**
 * rtXfailSection
 * 
 * Deals with information in the Xfail section
 *
 * @category  Testing
 * @package   RUNTESTS
 * @author    Zoe Slattery <zoe@php.net>
 * @author    Stefan Priebsch <spriebsch@php.net>
 * @copyright 2009 The PHP Group
 * @license   http://www.php.net/license/3_01.txt PHP License 3.01
 * @link      http://qa.php.net/
 */
class rtXfailSection extends rtInformationSection
{
    protected $failReason;

    protected function init()
    {
        //Only a single line reason is allowed. Ingore any more lines.
        if (isset($this->sectionContents[0])) {
            $this->failReason = $this->sectionContents[0];
        } else {
            $this->failReason = "This test is apparently expected to fail but the author did not say why";
        }
    }

    public function getReason()
    {
        return $this->failReason;
    }
}
?>
