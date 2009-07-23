<?php
/**
 * rtText
 *
 * rtText reads named texts from texts/ subdirectory
 * optionally replacing %n placeholders.
 * 
 * @category  Testing
 * @package   RUNTESTS
 * @author    Zoe Slattery <zoe@php.net>
 * @author    Stefan Priebsch <spriebsch@php.net>
 * @copyright 2009 The PHP Group
 * @license   http://www.php.net/license/3_01.txt PHP License 3.01
 * @link      http://qa.php.net/
 */
class rtText
{
    /**
     * Get a named text from texts/ subdirectory
     * and optionally replace placeholders %1 ... %n.
     *
     * @param string $name         Text name to return
     * @param array  $replacements Placeholder replacements
     *
     * @return string
     */
    public static function get($name, $replacements = array())
    {
        $filename = dirname(__FILE__) . '/texts/' . $name . '.txt';

        if (!file_exists($filename)) {
            throw new rtException('The text ' . $name . ' does not exist');
        }

        $text = file_get_contents($filename);

        // Replace %1 ... %n by the elements in replacements
        for ($i = 0; $i < count($replacements); $i++) {
            $text = str_replace('%' . ($i + 1), $replacements[$i], $text);
        }

        return $text;
    }
}
?>
