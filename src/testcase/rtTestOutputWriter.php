<?php
/**
 * rtTestOutputWriter
 *
 * Writes test output. This is concerned with status (PASS, FAIL etc) not
 * with the log files.
 *
 * @category   Testing
 * @package    RUNTESTS
 * @author     Zoe Slattery <zoe@php.net>
 * @author     Stefan Priebsch <spriebsch@php.net>
 * @author     Georg Gradwohl <g2@php.net>
 * @copyright  2009 The PHP Group
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 *
 */
abstract class rtTestOutputWriter
{
    protected $resultList = array();
    protected $output = NULL;
    protected $type = 'list';
    protected $overview = NULL;
    
    const OUTPUT_DIR = 'results';
    
    
    public static function getInstance($type='list')
    {
    	// defaults to 'list' - combatible with old version
        switch (($type)) {

        	case 'xml':
        		return new rtTestOutputWriterXML();
        		break;

        	case 'html':
        		
        		break;
        		
        	case 'csv':
        		return new rtTestOutputWriterCSV();
        		break;
        		
        	default:
        	case 'list':
        	case 'txt':
        		return new rtTestOutputWriterList();
        		break;
        	
        }
    } 
    
    
    public function setResultList(array $resultList)
    {
    	$this->resultList = $resultList;
    }
    

    
    
    /**
     * 
     */
    public function getOutput()
    {
        return $this->output;
    }
    
    
    
    
    public function write()
    {
    	$this->createOutput();
    	
    	if (!is_null($this->output)) {
    		
    	    if (!file_exists(self::OUTPUT_DIR)) {
	    		mkdir(self::OUTPUT_DIR);
	    	}

    		$file = self::OUTPUT_DIR.'/results_'.microtime(true).'.'.$this->type;
    		
    		if (file_put_contents($file, $this->output)) {
    			print "\nThe Test-Results were saved in <$file>\n";
    		} else {
    			print "\nError while saving results.\n";
    		}
    	}
    }
    
    
    public function getOverview($groups=NULL, $processCount=NULL)
    {
    	// if the overview was already created retun it
    	if (!is_null($this->overview)) {
    		return $this->overview;
    	}
    	
    	// collect data    	
    	$state = array();
    	$count = 0;
    	
    	foreach ($this->resultList as $result) {
    	 	
			$s = $result->getStatus()->__toString();
			
			if (!isset($state[$s])) {
				$state[$s] = 0;
			}
			
			$state[$s]++;
			$count++;
    	}
    	
    	// create the output-string
    	$str = '';
    	
    	$str .= "\n\n----------------------------------------\n";
    	$str .= "Tests:\t\t$count\n";
    	
    	if (is_numeric($groups)) {
    	    
    		$str .= "Groups:\t\t";
    		$blanks = strlen($count)-strlen($groups);
    		for ($b=0; $b<$blanks; $b++) {
    			$str .= ' ';
    		}
    		$str .= $groups."\n";
    	}
    	
    	if (is_numeric($processCount)) {
			
    		$str .= "Processes:\t";
    	    $blanks = strlen($count)-strlen($processCount);
    		for ($b=0; $b<$blanks; $b++) {
    			$str .= ' ';
    		}
    		$str .= $processCount."\n";
    	}
    	
		$str .= "----------------------------------------\n";

    	foreach ($state as $k => $v) {

    		$str .= strtoupper($k).":\t";
    		
    	   	$blanks = strlen($count)-strlen($v);
    		for ($b=0; $b<$blanks; $b++) {
    			$str .= ' ';
    		}
    		
    		$str .= $v;
    		
    		$p = round($v/$count*100,2);
    		
    	    $blanks = 5-strlen($v);
    		for ($b=0; $b<$blanks; $b++) {
    			$str .= ' ';
    		}

    		$str .= "($p%)\n";
    	}

    	$str .= "----------------------------------------\n";
    	
    	
    	$this->overview = $str;
    	return $str;
    }
    
    
    public function printOverview($groups=NULL, $processCount=NULL) {
    	
    	print $this->getOverview($groups, $processCount);
    	flush();
    }
    
    
    
    public static function flushResult(array $results, $state=0, $cid=NULL)
    {
    	switch ($state) {

    		case -1:	// no ouput
    			return;
    			break;

    			
    		default:
    		case 0: 	// a dot per test-case
    			
    			foreach ($results as $r) {
					print '.';
				}
    			break;

    			
    		case 1: 	// every test-case incl. status
    			print "\n";
				foreach ($results as $result) {
					print strtoupper($result->getStatus())."\t".$result->getName()."\n";
				}
    			break;

    			
    		case 2: 	// details about not-passed tests

				foreach ($results as $result) {
					
					$s = $result->getStatus();
					$name = $s->__toString();
					
					if ($name !== 'pass') {
						print "\n";
					}
					
					print strtoupper($name)."\t".$result->getName()."\n";

	    			 if ($name !== 'pass') {
	    			 	print "desc:\t".$result->getTitle()."\n";
	   			 		print "msg:\t".$s->getMessage($name)."\n";
	   			 		print "\n";
	    			 }
				}

    			break;

    			
    		case 3: 	// all available details

				foreach ($results as $result) {
					
					$s = $result->getStatus();
					$name = $s->__toString();

					print "\n";
					print strtoupper($name)."\t".$result->getName()."\n";
	    			print "desc:\t".$result->getTitle()."\n";
	   			 	print "msg:\t".$s->getMessage($name)."\n";

	   			 	if (!is_null($cid)) {
						print "CID:\t$cid\n";
					}
					
					print "mem:\t".round(memory_get_usage()/1024, 2)." kB\n";
					
					$files = $result->getSavedFileNames();
					
					if (sizeof($files) > 0) {
						
						print "files:\t";
						
						foreach ($files as $file) {
							print $file.', ';
						}
						
						print "\n";
					}

	   			 	
				}

    			break;
    	}
		
		flush();
    }
    
    
}
?>
