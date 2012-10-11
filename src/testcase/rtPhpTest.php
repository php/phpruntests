<?php
/**
 * rtPhpTest
 *
 * This class represents a single phpt test case.
 *
 * @category   Testing
 * @package    RUNTESTS
 * @author     Zoe Slattery <zoe@php.net>
 * @author     Stefan Priebsch <spriebsch@php.net>
 * @copyright  2009 The PHP Group
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 *
 */
class rtPhpTest
{
    public $testConfiguration;

    protected $testName;
    protected $contents;
    protected $testStatus;
    protected $output;
    protected $sections = array();
    protected $fileSection;
    protected $expectSection;
    protected $sectionHeadings;
    protected $redirectFromID = null;

    public function __construct(array $contents, $testName, $sectionHeadings, $runConfiguration, $testStatus, $redirectFromID=null)
    {
        $this->contents = $contents;
        $this->testName = $testName;
        $this->redirectFromID = $redirectFromID;
        $this->sectionHeadings = $sectionHeadings;
        $this->testStatus = $testStatus;
        $this->parse();
        $this->init($runConfiguration);
    }

    /**
     * Creates a section object for each test section
     */
    public function parse()
    {
        $lastSection = end($this->sectionHeadings);
        $start=0;

        foreach($this->sectionHeadings as $keyNumber => $sectionKey) {

            if($keyNumber < count($this->sectionHeadings) - 1) {
                $nextKey = $this->sectionHeadings[$keyNumber + 1];
            }

            $tempArray=array();
            for($index=$start; $index<count($this->contents); $index++)
            if($this->contents[$index] == "--".$sectionKey."--") {
                //Found the beginning of the section
                 
                for($contentsLine=$index + 1; $contentsLine<count($this->contents); $contentsLine ++) {

                    if( ($this->contents[$contentsLine] == "--".$nextKey."--") || ($contentsLine == count($this->contents))) {
                        //Found the end of the section OR the end of the test
                        $start = $contentsLine - 1;
                        if($this->isFileSection($sectionKey)) {
                            $tempArray = $this->removeDone($tempArray);
                        }                        
                        $testSection = rtSection::getInstance($sectionKey, $tempArray, $this->testName);
                        $this->sections[$sectionKey] = $testSection;
                        break;
                    } else {
                        $tempArray[] = $this->contents[$contentsLine];
                    }
                }
            }
        }
         
        $testSection = rtSection::getInstance($lastSection, $tempArray, $this->testName);
        $this->sections[$lastSection] = $testSection;


        //Identify the file and expect section types
        $this->fileSection = $this->setFileSection();
        $this->expectSection = $this->setExpectSection();
        
        //If the test is being run as a result of being redirected from somewhere else, update the TEST section with an identifier
        if($this->redirectFromID != null) {
        	$this->sections['TEST']->addString(' Redirected ' . $this->redirectFromID . ' test');
        }
    }


    /**
     * Initialises the configuration for this test. Uses the configuration sections from the test case
     *
     * @param rtRunTEstsConfiuration $runConfiguration
     *
     */
    public function init(rtRuntestsConfiguration $runConfiguration)
    {
        $this->testConfiguration = new rtTestConfiguration($runConfiguration, $this->sections, $this->sectionHeadings, $this->fileSection);
    }


    /**
     * Executes the test case
     *
     * @param rtRunTEstsConfiuration $runConfiguration
     */
    public function executeTest(rtRuntestsConfiguration $runConfiguration)
    {

        if (array_key_exists('SKIPIF', $this->sections)) {
            $this->testStatus = $this->sections['SKIPIF']->run($this, $runConfiguration);
            $this->testStatus->setExecutedPhpCommand($this->sections['SKIPIF']->getPhpCommand());
        }
        
        if (!$this->testStatus->getValue('skip')) {
            $this->testStatus = $this->fileSection->run($this, $runConfiguration);
            $this->testStatus->setExecutedPhpCommand($this->fileSection->getPhpCommand());
            
            //The test status can also be set to 'skip' if the PHP CGI is missing, so we need to check it again here.
            if(!$this->testStatus->getValue('skip')) {
                $this->output = $this->fileSection->getOutput();
                $this->compareOutput();

                if(array_key_exists('EXPECTHEADERS', $this->sections)) {
                    $this->headers = $this->fileSection->getHeaders();
                    $this->compareHeaders();
                }
            }
             

            if (array_key_exists('CLEAN', $this->sections)) {
                $this->testStatus = $this->sections['CLEAN']->run($this, $runConfiguration);
            }
        }
    }
    /**
     * 
     * Just runs the SKIPIF section - required for redirect test implementation
     * @param $runConfiguration... hmm, should have a testconfiguration?
     */
    
    public function runSkipif(rtRuntestsConfiguration $runConfiguration) {    
            $this->testStatus = $this->sections['SKIPIF']->run($this, $runConfiguration);
            $this->testStatus->setExecutedPhpCommand($this->sections['SKIPIF']->getPhpCommand());
     }

    /**
     * Test the output against the expect section
     *
     */
    public function compareOutput()
    {
        $result = $this->expectSection->compare($this->output);

        if (!$result) {
            $this->testStatus->setTrue('fail');
        } else {
            $this->testStatus->setTrue('pass');
        }
    }


    /**
     * Test the expected headers against actual headers. Only relevant for CGI tests.
     *
     */
    public function compareHeaders()
    {
        $result = $this->sections['EXPECTHEADERS']->compare($this->headers);

        if (!$result) {
            $this->testStatus->setTrue('fail_headers');
        } else {
            $this->testStatus->setTrue('pass_headers');
        }
    }


    /**
     * Identify a FILE section
     *
     */
    protected function isFileSection($key)
    {
        if (strpos($key, "FILE") !== false) {
            return true;
        }
        return false;
    }


    /*
     * Strip the lines after ===DONE=== from the file section of a test
     */
    protected function removeDone($array) {
        $result = array();
        foreach($array as $line) {
            $result[] = $line;
            // If found at the start of the line, so ' ===done===' won't work.
            if(stripos($line, "===done===") === 0) {
                break;
            }
        }
        return $result;
    }


    /**
     * Set the test's file section
     */
    protected function setFileSection()
    {
        if (array_key_exists('FILE', $this->sections)) {
            return $this->sections['FILE'];
        }

        if (array_key_exists('FILEEOF', $this->sections)) {
            return $this->sections['FILEEOF'];
        }

        if (array_key_exists('FILE_EXTERNAL', $this->sections)) {
            return $this->sections['FILE_EXTERNAL'];
        }
    }


    /**
     * Sets the test's expect section
     */
    protected function setExpectSection()
    {
        if (array_key_exists('EXPECT', $this->sections)) {
            return $this->sections['EXPECT'];
        }

        if (array_key_exists('EXPECTF', $this->sections)) {
            return  $this->sections['EXPECTF'];
        }

        if (array_key_exists('EXPECTREGEX', $this->sections)) {
            return $this->sections['EXPECTREGEX'];
        }
    }

    public function getName()
    {
        return $this->testName;
    }

    public function getOutput()
    {
        return $this->output;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function hasSection($sectionKey)
    {
        return array_key_exists($sectionKey, $this->sections);
    }

    public function getSection($sectionKey)
    {
        return $this->sections[$sectionKey];
    }

    /*
     * Return the object containing all test status
     */
    public function getStatus()
    {
        return $this->testStatus;
    }

    public function getFileSection()
    {
        return $this->fileSection;
    }

    public function getExpectSection()
    {
        return $this->expectSection;
    }
}
?>
