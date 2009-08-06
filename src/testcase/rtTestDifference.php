<?php
/**
 * rtTestDifference
 *
 * Calculates difference between actual and expected output.
 * Exact import from run-tests.php
 *
 * @category   Testing
 * @package    RUNTESTS
 * @author     Zoe Slattery <zoe@php.net>
 * @author     Stefan Priebsch <spriebsch@php.net>
 * @copyright  2009 The PHP Group
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 *
 */
class rtTestDifference
{
    protected $difference;
    protected $expectedOutput;
    protected $output;
    protected $expectedRegularExpression;

    public function __construct(rtOutputSection $expectedOutput, $output)
    {
        
        $this->expectedOutput = $expectedOutput->getContents(); 
        
        //Expect a regular expression from EXPECTF or EXPECTREGEX but not from EXPECT sections
        $is_reg = true; 
        if(get_class($expectedOutput) == 'rtExpectSection') {
            $is_reg = false;
        }
        $this->expectedRegularExpression = explode(b"\n", $expectedOutput->getPattern());
        $this->output = explode(b"\n", $output);
        
        $this->generateDiff($is_reg);
    }

    public function generateDiff($is_reg)
    {
        $this->difference = $this->generateArrayDiff($this->expectedRegularExpression, $this->output, $is_reg, $this->expectedOutput);
    }

    /**
     * Return the difference
     *
     * @return array
     */
    public function getDifference()
    {
        return $this->difference;
    }

    /**
     * Unmodified from the original run-tests.php
     *
     * @param array $ar1
     * @param array $ar2
     * @param boolean $is_reg
     * @param array $w
     * @return array
     */
    public function generateArrayDiff($ar1, $ar2, $is_reg, $w)
    {
         
        
        $idx1 = 0; $ofs1 = 0; $cnt1 = @count($ar1);
        $idx2 = 0; $ofs2 = 0; $cnt2 = @count($ar2);
        $diff = array();
        $old1 = array();
        $old2 = array();

        while ($idx1 < $cnt1 && $idx2 < $cnt2) {

            if ($this->compLine($ar1[$idx1], $ar2[$idx2], $is_reg)) {
                $idx1++;
                $idx2++;
                continue;
            } else {

                $c1 = @$this->countArrayDiff($ar1, $ar2, $is_reg, $w, $idx1+1, $idx2, $cnt1, $cnt2, 10);
                $c2 = @$this->countArrayDiff($ar1, $ar2, $is_reg, $w, $idx1, $idx2+1, $cnt1, $cnt2, 10);

                if ($c1 > $c2) {
                    $old1[$idx1] = (binary) sprintf("%03d- ", $idx1+1) . $w[$idx1++];
                    $last = 1;
                } else if ($c2 > 0) {
                    $old2[$idx2] = (binary) sprintf("%03d+ ", $idx2+1) . $ar2[$idx2++];
                    $last = 2;
                } else {
                    $old1[$idx1] = (binary) sprintf("%03d- ", $idx1+1) . $w[$idx1++];
                    $old2[$idx2] = (binary) sprintf("%03d+ ", $idx2+1) . $ar2[$idx2++];
                }
            }
        }

        reset($old1); $k1 = key($old1); $l1 = -2;
        reset($old2); $k2 = key($old2); $l2 = -2;

        while ($k1 !== null || $k2 !== null) {

            if ($k1 == $l1 + 1 || $k2 === null) {
                $l1 = $k1;
                $diff[] = current($old1);
                $k1 = next($old1) ? key($old1) : null;
            } else if ($k2 == $l2 + 1 || $k1 === null) {
                $l2 = $k2;
                $diff[] = current($old2);
                $k2 = next($old2) ? key($old2) : null;
            } else if ($k1 < $k2) {
                $l1 = $k1;
                $diff[] = current($old1);
                $k1 = next($old1) ? key($old1) : null;
            } else {
                $l2 = $k2;
                $diff[] = current($old2);
                $k2 = next($old2) ? key($old2) : null;
            }
        }

        while ($idx1 < $cnt1) {
            $diff[] = (binary) sprintf("%03d- ", $idx1 + 1) . $w[$idx1++];
        }

        while ($idx2 < $cnt2) {
            $diff[] = (binary) sprintf("%03d+ ", $idx2 + 1) . $ar2[$idx2++];
        }

        return $diff;
    }

    /**
     * Unmodified from the original version of runt-tests.php
     *
     * @param array $ar1
     * @param array $ar2
     * @param boolean $is_reg
     * @param array $w
     * @param int $idx1
     * @param int $idx2
     * @param int $cnt1
     * @param int $cnt2
     * @param int $steps
     * @return unknown
     */
    public function countArrayDiff($ar1, $ar2, $is_reg, $w, $idx1, $idx2, $cnt1, $cnt2, $steps)
    {
        $equal = 0;

        while ($idx1 < $cnt1 && $idx2 < $cnt2 && $this->compLine($ar1[$idx1], $ar2[$idx2], $is_reg)) {
            $idx1++;
            $idx2++;
            $equal++;
            $steps--;
        }

        if (--$steps > 0) {
            $eq1 = 0;
            $st = $steps / 2;

            for ($ofs1 = $idx1 + 1; $ofs1 < $cnt1 && $st-- > 0; $ofs1++) {
                $eq = @$this->countArrayDiff($ar1, $ar2, $is_reg, $w, $ofs1, $idx2, $cnt1, $cnt2, $st);

                if ($eq > $eq1) {
                    $eq1 = $eq;
                }
            }

            $eq2 = 0;
            $st = $steps;

            for ($ofs2 = $idx2 + 1; $ofs2 < $cnt2 && $st-- > 0; $ofs2++) {
                $eq = @$this->countArrayDiff($ar1, $ar2, $is_reg, $w, $idx1, $ofs2, $cnt1, $cnt2, $st);
                if ($eq > $eq2) {
                    $eq2 = $eq;
                }
            }

            if ($eq1 > $eq2) {
                $equal += $eq1;
            } else if ($eq2 > 0) {
                $equal += $eq2;
            }
        }

        return $equal;
    }


    /**
     * Copied unmodified from run-tests.php
     *
     * @param string $l1
     * @param string $l2
     * @param boolean $is_reg
     * @return boolean
     */
    public function compLine($l1, $l2, $is_reg)
    {
        if ($is_reg) {
            return preg_match((binary) "/^$l1$/s", (binary) $l2);
        } else {
            return !strcmp((binary) $l1, (binary) $l2);
        }
    }
}
?>
