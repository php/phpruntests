<?php
/**
 * rtPhpRunner
 * 
 * Runs PHP code
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
class rtPhpRunner
{
    protected $phpCommand;
    protected $environmentVariables;
    protected $currentWorkingDirectory;
    protected $stdin;
    protected $timeOut;

    public function __construct($phpCommand, $environmentVariables=null, $currentWorkingDirectory=null, $stdin = null, $timeOut = 60)
    {
        $this->phpCommand = $phpCommand;
        $this->environmentVariables = $environmentVariables;
        $this->currentWorkingDirectory = $currentWorkingDirectory;
        $this->timeOut = $timeOut;
        $this->stdin = $stdin;
    }

    /**
     * Runs the PHP code.
     *
     * @return string - the output from the code
     */
    public function runphp()
    {
        $data = '';

        $proc = proc_open(
            $this->phpCommand,
            array(0 => array('pipe', 'r'), 1 => array('pipe', 'w'), 2 => array('pipe', 'w')), 
            $pipes, 
            $this->currentWorkingDirectory, 
            $this->environmentVariables,
            array('suppress_errors' => true, 'binary_pipes' => true)
        );

        if (!$proc) {
            throw new rtException('Failed to open process to run PHP code in rtPhpRunner');
        }

        if (!is_null($this->stdin)) {
            fwrite($pipes[0], (binary) $this->stdin);
        }

        fclose($pipes[0]);

        while (true) {
            /* hide errors from interrupted syscalls */
            $r = $pipes;
            $w = null;
            $e = null;
             
            $n = @stream_select($r, $w, $e, $this->timeOut);

            if ($n === false) {
                throw new rtException('Stream select failure in rtPhpRunner');
            } else if ($n === 0) {
                proc_terminate($proc);
                throw new rtException ('The process running test code has timed out in rtPhpRunner');
            } else if ($n > 0) {
                $line = fread($pipes[1], 8192);
                if (strlen($line) == 0) {
                    /* EOF */
                    break;
                }
                $data .= (binary) $line;
            }
        }

        /* check the process status. Note that this will always be FALSE on windows */
        $stat = proc_get_status($proc);
        if ($stat['signaled']) {
            throw new rtException('The process was terminated by uncaught signal number in rtPhpRunners');
        }

        $code = proc_close($proc);
        return $data;
    }
}
?>
