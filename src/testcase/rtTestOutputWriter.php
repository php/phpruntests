<?php
/**
 * rtTestOutputWriter
 *
 * Writes test output.
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

    
    public static function getInstance($type='list')
    {
    	// defaults to 'list' - combatible with old version
        switch (($type)) {

        	case 'xml':
        		return new rtTestOutputWriterXML();
        		break;

        	case 'html':
        		return new rtTestOutputWriterHTML();
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

    
    /**
     * 
     * @param array $resultList    an array of arrays (testgroups) of rtTestResults
     */
    public function setResultList(array $resultList)
    {
    	$this->resultList = $resultList;
    }

    
	/**
	 * 
	 * @return string $output
	 */
    public function getOutput()
    {
        return $this->output;
    }
    

    /**
     * writes the the results to a file
     * 
     * @param $filename 
     */
    public function write($filename=null)
    {
    	$this->createOutput();
    	
    	if (!is_null($this->output)) {

	    	if (is_null($filename)) {
    			$filename = 'results_'.round(microtime(true)).'.'.$this->type;
	    	}
    		
    		if (file_put_contents($filename, $this->output)) {
    			print "\nThe Test-Results were saved in <$filename>\n";
    		} else {
    			print "\nError while saving results.\n";
    		}
    	}
    }
    
    
    /**
     * creates an overview about the test-results
     * 
     * @param  integer $groups
     * @param  integer $processCount
     * @return string
     */
    public function getOverview($parallelGroups = 0, $serialGroups= 0, $processCount, $skippedGroups)
    {
    	// if the overview was already created retun it
    	if (!is_null($this->overview)) {
    		return $this->overview;
    	}
    
    	/*
    	 * Add one to the process count if it's 0. There must always be one process - right?
    	 */
    	if($processCount == 0) {
    		$processCount ++;
    	}
    	
    	// collect data    	
    	$state = array();
    	$count = 0;
    	
    	foreach ($this->resultList as $groupResult) { 
    		foreach($groupResult as $name=>$testStatus) {   	 	
				$s = $testStatus->__toString();
				
				if (!isset($state[$s])) {
					$state[$s] = 0;
				}
				
				$state[$s]++;
				$count++;
    		}
    	}
    	
    	// create the output-string
    	$secondColPosition = 30;
    	$str = '';
    	
    	$str .= "\n\n----------------------------------------\n";
    	$str .= "Number of skipped groups:";
    	$blanks = 30 - strlen("Number of skipped groups:") - strlen(count($skippedGroups));
    	$str = $this->writeBlanks($str, $blanks);
    	$str .= count($skippedGroups);
    	$str .= "\n----------------------------------------\n";
    	
    	$str .= "Tests:";
    	$blanks = 30 - strlen("Tests:") - strlen($count);    	
    	$str = $this->writeBlanks($str, $blanks);
    	$str .= "$count\n";
    	
    	if (is_numeric($parallelGroups)) {
    	    
    		$str .= "Parallel Groups:";
    		$blanks = 30 - strlen("Parallel Groups:") - strlen($parallelGroups);
    		$str = $this->writeBlanks($str, $blanks);
    		$str .= $parallelGroups."\n";
    	}
    	
    	if (is_numeric($serialGroups)) {
    	    
    		$str .= "Serial Groups:";
    		$blanks = 30 - strlen("Serial Groups:") - strlen($serialGroups);
    		$str = $this->writeBlanks($str, $blanks);
    		$str .= $serialGroups."\n";
    	}
    	
    	if (is_numeric($processCount)) {
			
    		$str .= "Processes:";
    	    $blanks = 30 -strlen("Processes:") - strlen($processCount);
    		$str = $this->writeBlanks($str, $blanks);
    		$str .= $processCount."\n";
    	}
    	
		$str .= "----------------------------------------\n";
		
    	foreach ($state as $k => $v) {

    		$str .= substr(strtoupper($k), 0, 5).":\t";
    		
    	   	$blanks = strlen($count)-strlen($v);
    		for ($b=0; $b<$blanks; $b++) {
    			$str .= ' ';
    		}
    		
    		$str .= $v.' ';
    		
    		$p = round($v/$count*100,2);
    		
    	    $blanks = 5-strlen($p);
    		for ($b=0; $b<$blanks; $b++) {
    			$str .= ' ';
    		}

    		$str .= "($p%)\n";
    	}

    	$str .= "----------------------------------------\n";
    	
    	
    	$this->overview = $str;
    	return $str;
    }
    
    
    public function printOverview($parallelGroups=NULL, $serialGroups = NULL, $processCount=NULL, $skippedGroups=null) {
    	
    	print $this->getOverview($parallelGroups, $serialGroups, $processCount, $skippedGroups);
    	flush();
    }
    
    
    /**
     * prints out the results of a testgroup
     * 
     * @param array $results
     * @param $state
     * @param $cid
     */
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
				foreach ($results as $name=>$status) {
					print strtoupper($status->__toString())."\t".$name.".phpt\n";
				}
    			break;

    		case 2: 	// details about not-passed tests

				foreach ($results as $name=>$s) {
					
					
					$status = $s->__toString();
					
					if ($status !== 'pass') {
						print "\n";
					}
					
					print strtoupper($s)."\t".$name.".phpt\n";

	    			 if ($s !== 'pass') {
	    			 		    			 	
		    			$msg = $s->getMessage($name);
		    			if (!is_null($msg)) {
		    				print "MSG:\t".$msg."\n";
		    			}

	   			 		print "\n";
	    			 }
				}

    			break;

    		case 3: 	// all available details
    		   
				foreach ($results as $name=>$s) {
					
					$status = $s->__toString();
					
					print "\n";
					
					print strtoupper($status)."\t".$name.".phpt\n";
	    			
	    			$msg = $s->getMessage($status);

	    			if (!is_null($msg)) {
	    				print "MSG:\t".$msg."\n";
	    			}

	   			 	if (!is_null($cid)) {
						print "CID:\t$cid\n";
					}

					print "MEM:\t".round(memory_get_usage()/1024, 2)." kB\n";
					
					$files = $s->getSavedFileNames();
					
					if (sizeof($files) > 0) {
						
						print "FILES:\n";
						foreach ($files as $t => $file) {
							print "$t:\t$file\n";
						}
					}
					
					$cmd = $s->getExecutedPhpCommand();
					if (!is_null($cmd)) {
						print "PHP-COMMAND: $cmd\n";
					}
				}
    			break;
    	}
		
		flush();
    }
    
    public function writeBlanks($str, $n) {
    for ($b=0; $b<$n; $b++) {
    			$str .= ' ';
    		}
    return $str;
    }
    
}
?>