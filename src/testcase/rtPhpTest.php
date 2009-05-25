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

    private $testName;
    private $contents;
    private $status;
    private $output;
    private $sections;
    private $fileSection;
    private $expectSection;
    private $sectionHeadings;

    public function __construct(array $contents, $testName, $sectionHeadings, $runConfiguration)
    {
        $this->contents = $contents;
        $this->testName = $testName;
        $this->sectionHeadings = $sectionHeadings;
        $this->parse();
        $this->init($runConfiguration);
    }

    /**
     * Creates a section object for each test section
     */
    public function parse()
    {
        for ($i=0; $i<count($this->contents); $i++) {
            //Create an array of section objects
            if ($this->isSectionKey($this->contents[$i])) {
                $sectionKey = $this->contents[$i];

                $tempArray = array();
                for ($j=$i+1; $j<count($this->contents); $j++) {
                     
                    if ($this->isSectionKey($this->contents[$j]) || stripos($this->contents[$j], "===done===") !== false) {
                        if(stripos($this->contents[$j], "===done===") !== false) {
                            $tempArray[] = trim($this->contents[$j]);
                        }
                        $testSection = rtSection::getInstance($sectionKey, $tempArray);
                        $this->sections[$sectionKey] = $testSection;
                        break;
                    }
                    $tempArray[] = $this->contents[$j];
                }
            }
        }

        $testSection = rtSection::getInstance($sectionKey, $tempArray);
        $this->sections[$sectionKey] = $testSection;
        

        //Identify the file and expect section types
        $this->fileSection = $this->setFileSection();
        $this->expectSection = $this->setExpectSection();

        $this->fileSection->setExecutableFileName($this->getName());
    }
    

    /**
     * Initialises the configuration for this test. Uses the configuration sections from teh test case
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
        $this->status = array();

        if (array_key_exists('SKIPIF', $this->sections)) {
            $this->status = $this->sections['SKIPIF']->run($this, $runConfiguration);
        }

        if (!array_key_exists('skip', $this->status) && !array_key_exists('bork', $this->status)) {
            $this->status = array_merge($this->status, $this->fileSection->run($this, $runConfiguration));
            //The test can be skipped by file sections if the CGI executable is not available
            if(!array_key_exists('skip', $this->status)) {
                $this->output = $this->fileSection->getOutput();
                $this->compareOutput();

                if(array_key_exists('EXPECTHEADERS', $this->sections)) {
                    $this->headers = $this->fileSection->getHeaders();
                    $this->compareHeaders();
                }
            }
             

            if (array_key_exists('CLEAN', $this->sections)) {
                $cleanStatus = $this->sections['CLEAN']->run($this, $runConfiguration);
                $this->status = array_merge($this->status, $cleanStatus);
            }
        }
    }

    /**
     * Test the output against the expect section
     * 
     */
    public function compareOutput()
    {
        $result = $this->expectSection->compare($this->output);

        if ($result) {
            $this->status['pass'] = '';
        } else {
            $this->status['fail'] = 'output';
        }
    }

    
    /**
     * Test the expected headers against actual headers. Only relevant for CGI tests.
     * 
     */
    public function compareHeaders()
    {
        $result = $this->sections['EXPECTHEADERS']->compare($this->headers);

        if ($result) {
            $this->status['pass'] = '';
        } else {
            $this->status['fail'] = 'headers';
        }
    }

    
    /**
     * Identify a section heading
     * 
     */
    private function isSectionKey($line)
    {
        if (in_array($line, $this->sectionHeadings)) {
            return true;
        }
        return false;
    }


    /**
     * Set the test's file section
     */
    private function setFileSection()
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
    private function setExpectSection()
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

    public function getStatus()
    {
        return $this->status;
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
