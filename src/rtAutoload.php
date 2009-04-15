<?php

rtAutoload::init();

/**
 * Autoloader using a map file (rtClassMap.php)
 * defining the file to load each class from.
 */
class rtAutoload
{
  /**
   * @var array
   */
  protected static $classMap;

  /**
   * @var string
   */
  protected static $classPath;


  /**
   * Initialize the autoloader
   *
   * @return null
   */
  public static function init()
  {
    self::$classPath = dirname(__FILE__);

    if (substr(self::$classPath, -1) != '/') {
      self::$classPath .= '/';
    }

    if (file_exists(self::$classPath . 'rtClassMap.php')) {
      include self::$classPath . 'rtClassMap.php';
      self::$classMap = $rtClassMap;
    }

    if (function_exists('__autoload')) {
      spl_autoload_register('__autoload');
    }

    spl_autoload_register(array('rtAutoload', 'autoload'));
  }


  /**
   * Autoload method
   *
   * @param string $class Class name to autoload
   * @return null
   */
  public static function autoload($class)
  {
    if (isset(self::$classMap[$class])) {
      include self::$classPath . self::$classMap[$class];
    }
  }
}

?>
