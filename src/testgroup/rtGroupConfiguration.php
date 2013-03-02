<?php
/**
 * rtGroupConfiguration
 *
 * Defines environment for a group of test. Could be either setting group 
 * environment variables (as in redirected tests) of setting parameters 
 * to stop the group being run in parallel
 *
 * @category   Testing
 * @package    RUNTESTS
 * @author     Zoe Slattery <zoe@php.net>
 * @author     Stefan Priebsch <spriebsch@php.net>
 * @copyright  2009 The PHP Group
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 *
 */
class rtGroupConfiguration
{
	protected $testDirectory;
	protected $environmentVariables = array();
	protected $isRedirect=false;
	protected $serialGroup=false;
	protected $redirectFromID = null;
	protected $skipFile = "";
	protected $hasSkipCode = false;
	
	const SKIP_FILE_NAME = "skip_group_if.inc";
	

    public function __construct($directory)
    {   	
        $this->testDirectory = $directory;
    }
    
    public function parseRedirect(rtPHPTest $redirectedTest) {
    		
    	$name = $redirectedTest->getName();
    	
    	$code = implode($redirectedTest->getSection('REDIRECTTEST')->getContents(), "\n");
    	
    	//Strip tabs which interfere with eval
    	$code = preg_replace('|\\t|', ' ', $code);
    	
    	$setup = eval($code);
    		
        $this->environmentVariables = $setup['ENV'];
        
        
        //Remove trailing white space and add a slash to the directory name
        //Will need modification if we wanted to be able to give it a list of test directories.
        
        $dir = trim($setup['TESTS']);
        if (substr($dir, -1) !== "/") {$dir .= "/";}
        
        /*
         * Some of the PDO tests have __DIR__ in front of them, means you have to run then from the same place 
         * as run-tests.php. This is really annoying because in this case it just picks up the location of this class
         * which is useless for anything.
         */

        
        $stripchars = strlen(__DIR__) + 1;

        if ( substr($dir, 0, strlen(__DIR__)) === __DIR__) {
         	$dir = substr($dir, $stripchars);
        }
        
        /*
         * However... we do need some way of finding the absolute path of the tests. I think it is reasonable to assume 
         * that they are related to the the path of the test that is redirecting to them.
         * Take the first three characters of the directory and try to find them in the name of the redirected test.
         * The best way to do this is really for the test case to specify a path relative to the test tgey are redirected from
         * but this would mean changes to run-tests.php and to the tests. 
         */
        
        $key = substr($dir, 0, 3);
         
        //Find the key in the full name of the test contains the redirect          
        $position = strpos($name, $key); 
        
        //Take the root string from before the key
        $root=substr($name, 0, $position);
        
        $title = $redirectedTest->getSection('TEST')->getContents();
        $this->redirectFromID = $title[0];
        
        //And add it on to form a full directory path.
        $dir = $root . $dir;
        
        $this->testDirectory = $dir;
        
        $this->isRedirect = true;
        
        //Finaly add the directory name to the env variables - checked by SKIPIF
        $this->environmentVariables['REDIR_TEST_DIR'] = $dir;
        
    	
    }
    
    public function parseConfiguration() {
    	//TODO Could insert code to read a config file from the test directory that determines whether the set of tests should be run
    	//in parallel or not.
          	
        //Code to read the directory skipif, run it and skip the directory
        //TODO- this makes a miniscule difference to timing.
    	if(file_exists($this->testDirectory. "/" . self::SKIP_FILE_NAME)) {
    		$this->hasSkipCode = true;
    		$this->skipFile = $this->testDirectory. "/" .self::SKIP_FILE_NAME;  
    	}
    	return;
    	
    }
    
    
    public function getEnvironmentVariables() {
    	return $this->environmentVariables;
    }
	public function getTestDirectory() {
    	return $this->testDirectory;
    }
    public function isRedirect() {
    	return $this->isRedirect;
    }
    public function isSerial() {
    	return $this->serialGroup;
    } 
    public function getRedirectFromID() {
    	return $this->redirectFromID;
    } 
    public function hasSkipCode() {
    	return $this->hasSkipCode;
    } 
     public function getSkipFile() { 
            
    	return $this->skipFile;
    } 
}

