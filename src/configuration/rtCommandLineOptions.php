<?php

/**
 * Parse command line options
 *
 * @package  control
 * @author   Zoe Slattery <zoe.slattery@googlemail.com>
 * @license  PHP http://www.php.net/license/3_01.txt
 *
 */
class rtCommandLineOptions
{
/**
 * 
 */
  protected $shortOptions = array(
    'n',
    'm',
    'q',
    'x',
    'v',
    'h',
    'z',  //parallel - run out of obvious letters
  );

  protected $shortOptionsWithArgs = array(
    'l',
    'r',
    'w',
    'a',
    'c',
    'd',
    'p',
    's',
  );

  protected $longOptions = array(
    'verbose',
    'help',
    'keep-all',
    'keep-php',
    'keep-skip',
    'keep-clean',
    'keep-out',
    'keep-exp',
    'show-all',
    'show-php',
    'show-skip',
    'show-clean',
    'show-exp',
    'show-diff',
    'show-out',
    'no-clean',
  );

  protected $longOptionsWithArgs = array(
    'html',
    'temp-source',
    'temp-target',
    'set-timeout',
  );
                              
  protected $options = array();

  protected $testFilename = array();

  /**
   * Enter description here...
   *
   */

  public function __construct()
  {
  }


  public function parse($argv)
  {
    $this->parseCommandLineOptions($this->stripSpaces($argv));
  }
  

  protected function isShortOption($arg)
  {
    return (substr($arg, 0, 1) == '-') && (substr($arg, 1, 1) != '-');
  }
  

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
   */
  public function parseCommandLineOptions($argv)
  {
    for ($i=0; $i<count($argv); $i++)
    {

      if (!$this->isShortOption($argv[$i]) && !$this->isLongOption($argv[$i]))
      {
        $this->testFilename[] = $argv[$i];
        continue;
      }

      if ($this->isShortOption($argv[$i]))
      {
        $option = substr($argv[$i], 1);
      } else {
        $option = substr($argv[$i], 2);
      }

      if (!in_array($option, array_merge($this->shortOptions, $this->shortOptionsWithArgs, $this->longOptions, $this->longOptionsWithArgs)))
      {
        throw new rtUnknownOptionException('Unknown option ' . $argv[$i]);
      }

      if (in_array($option, array_merge($this->shortOptions, $this->longOptions)))
      {
        $this->options[$option] = true;
        continue;
      }

      if (!$this->isValidOptionArg($argv, $i + 1))
      {
        throw new rtMissingArgumentException('Missing argument for command line option ' . $argv[$i]);
      }

      $i++;
      $this->options[$option] = $argv[$i];
    }
  }


  /**
   * Checks to make sure that the arument following does not begin with "-"
   * @param string - argument
   * @return bool
   */
  public function isValidOptionArg($array, $index)
  {
    if (!isset($array[$index]))
    {
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

    for ($i=1; $i<count($argv); $i++)
    {
      if ($argv[$i] != "") {
        $result[] = $argv[$i];
      }
    }
    return $result;
  }


  /**
   *
   */
  public function getOption($option)
  {
    if (!isset($this->options[$option])) {
      return false;
    }
    return $this->options[$option];
  }


  /**
   * Check whether an option exists
   */
  public function hasOption($option)
  {
    return isset($this->options[$option]);
  }


  /**
   * Return the array containing file names or directory names to be tested
   */
  public function getTestFilename()
  {
    return $this->testFilename;
  }
  

}

?>
