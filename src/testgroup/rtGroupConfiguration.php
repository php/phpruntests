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
	

    public function __construct(rtRuntestsConfiguration $runConfiguration, $directory)
    {
        $this->testDirectory = $directory;
    }
    
    public function parseRedirect(rtPHPTest $redirectedTest) {
    	var_dump($redirectedTest->getSection('REDIRECTTEST'));   	
    }
}

