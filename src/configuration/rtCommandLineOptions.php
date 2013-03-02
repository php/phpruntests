<?php
/**
 * rtCommandLineOptions
 *
 * Parse command line options for run-tests.php
 * 
 * @category   Testing
 * @package    RUNTESTS
 * @author     Zoe Slattery <zoe@php.net>
 * @author     Stefan Priebsch <spriebsch@php.net>
 * @copyright  2009 The PHP Group
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link       http://qa.php.net/
 * 
 */
class rtCommandLineOptions
{
    /**
     * @var array
     */
    protected $shortOptions = array(
        'n', // pass -n option to the php binary
        'm', // memory-check (valgrind)
        'q', // quiet, no user interaction
        'v', // verbose-mode level 1
        'h', // help
        'g', // attempt to group tasks for parallel run
    );

    /**
     * @var array
     */
    protected $shortOptionsWithArgs = array(
        'l', // read the testfiles to be executed from
        'r', // read the testfiles to be executed from
        'c', // look for php.ini
        'd', // pass option to the php binary
        'p', // specify php executable
        's', // save - defines the filename to store the results
        'o', // output type (list, xml, csv...)
    	'z', // parallel - run out of obvious letters
    );

    /**
     * @var array
     */
    protected $longOptions = array(
        'vv',	      // verbose-mode level 2
    	'vvv',	      // verbose-mode level 3
        'help',	      // help
        'keep-all',   // keep all files
        'keep-php',   // keep only php files
        'keep-skip',  // keep only skip files
        'keep-clean', // keep only clean files
        'keep-out',   // keep only out files
        'keep-exp',   // keep only exp files
        'no-clean',   // do not execute clean section
        'debug'   // extra debug info     
    );

    /**
     * @var array
     */
    protected $longOptionsWithArgs = array(
        'mopts',	// memory-options (valgrind)
        'log',     //log to a file name
    );
                              
    /**
     * @var array
     */
    protected $options = array();

    /**
     * @var array
     */
    protected $testFilename = array();

    /**
     * Constructs the object
     */
    public function __construct()
    {
    }

    /**
     * Parse the command line options
     *
     * @param array $argv Command line arguments
     * @return void
     */
    public function parse($argv)
    {
        $this->parseCommandLineOptions($this->stripSpaces($argv));
    }
  
    /**
     * Returns true when argument is a short option,
     * i.e. begins with -. To differentiate from long option (--)
     * we also look at the second character, which may not be a - character.
     *
     * @param string $arg Single command line argument
     * @return bool
     */
    protected function isShortOption($arg)
    {
        return (substr($arg, 0, 1) == '-') && (substr($arg, 1, 1) != '-');
    }

    /**
     * Returns true when argument is a long option,
     * i.e. begins with --
     *
     * @param string $arg Single command line argument
     * @return bool
     */
    protected function isLongOption($arg)
    {
        return substr($arg, 0, 2) == '--';
    }

    /**
     * Parse command line, expects to find:
     * 	-{shortOption}
     * 	-{shortOptionWithArg} {arg}
     *	--{longOption}
     *	--{longOptionWithArg} {arg}
     * 	file name|directory name
     *
     * @param array $argv Command line arguments
     * @return void
     */
    public function parseCommandLineOptions($argv)
    {
        for ($i=0; $i<count($argv); $i++) {

            if (!$this->isShortOption($argv[$i]) && !$this->isLongOption($argv[$i])) {
                $this->testFilename[] = $argv[$i];
                continue;
            }

            if ($this->isShortOption($argv[$i])) {
                $option = substr($argv[$i], 1);
            } else {
                $option = substr($argv[$i], 2);
            }

            if (!in_array($option, array_merge($this->shortOptions, $this->shortOptionsWithArgs, $this->longOptions, $this->longOptionsWithArgs))) {
                throw new rtException('Unknown option ' . $argv[$i]);
            }

            if (in_array($option, array_merge($this->shortOptions, $this->longOptions))) {
                $this->options[$option] = true;
                continue;
            }

            if (!$this->isValidOptionArg($argv, $i + 1)) {
                throw new rtException('Missing argument for command line option ' . $argv[$i]);
            }

            $i++;
            $this->options[$option] = $argv[$i];
        }
        
        if(empty($this->testFilename)) {
        	//set default options if being run from PHP top level directory (as in make test)
        	if(file_exists(getcwd() . "/" . "sapi/cli/php")) {
        		$this->testFilename = array('tests', 'ext', 'Zend', 'ZendEngine2', 'sapi/cli', 'sapi/cgi');
        	}
        }
    }

    /**
     * Checks to make sure that the arument following does not begin with "-"
     *
     * @param array  $array arguments
     * @param string $index array index
     * @return bool
     */
    public function isValidOptionArg($array, $index)
    {
        if (!isset($array[$index])) {
            return false;
        }

        return substr($array[$index], 0, 1) != '-';
    }


    /**
     * Removes spaces in command line arguments AND strips $argv[0]
     * @param array - command line arguments
     * @return array - command line args with blank entries removed
     */
    protected function stripSpaces($argv)
    {
        $result = array();

        for ($i=1; $i<count($argv); $i++) {
            if ($argv[$i] != "") {
                $result[] = $argv[$i];
            }
        }

        return $result;
    }


    /**
     * Returns the value of given option.
     *
     * @param string $option option name
     */
    public function getOption($option)
    {
        if (!isset($this->options[$option])) {
            return false;
        }

        return $this->options[$option];
    }


    /**
     * Check whether an option exists.
     *
     * @param string $option option name
     * @return bool
     */
    public function hasOption($option)
    {
        return isset($this->options[$option]);
    }


    /**
     * Return the array containing file names or directory names to be tested.
     *
     * @return string
     */
    public function getTestFilename()
    {
        return $this->testFilename;
    }
}
?>
