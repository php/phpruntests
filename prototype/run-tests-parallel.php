<?php
class PhpTestRun {
	private $directory_list;
	private $test_directory_list;
	private $maxp;

	public function __construct($root,$executable, $run_tests) {
		$this->root=$root;
		$this->directory_list=array();
		$this->set_directory_list($root,$this->directory_list);
		$this->test_directory_list=array();
		$this->set_test_directory_list();
		$this->php_executable=$executable;
		$this->run_tests=$run_tests;
	}
	public function set_maxp($maxp) {
		$this->maxp = $maxp;
	}
	public function get_maxp() {
		return $this->maxp;
	}
	/*
	 * Run the tests in parallel by directory
	 */
	public function run_all_tests_parallel($out_dir) {
		$maxp=$this->maxp;
		
		$test_directory_list = $this->test_directory_list;
		
		while (!empty($test_directory_list)) {
			$suite=array_pop($test_directory_list);

			$pid = pcntl_fork();
			 
			if ($pid == -1) {
				die("could not fork");
			} elseif ($pid) {
				//echo "I'm the Parent $i\n";
				$execute++;
				if ($execute>=$maxp){
					pcntl_wait($status);
					if(pcntl_wifexited($status)) {
						$code = pcntl_wexitstatus($status);
						print "pid, $pid returned exit code: $code\n";
					}
					else {
						print "$pid was unnaturally terminated\n";
					}
					$execute--;
				}
			} else {
				$this->run_suite($suite, $out_dir);
				exit;
			}
		}
		// Collect status of remaining children
		for ($i=0; $i<$maxp; $i++) {
			pcntl_wait($status);
			if(pcntl_wifexited($status)) {
				$code = pcntl_wexitstatus($status);
				print "pid, $pid returned exit code: $code\n";
			}else {
				print "$pid was unnaturally terminated\n";
			}
		}
		
	}
	public function run_suite($test_suite, $out_dir)
	{
		$my_pid = getmypid();
		print "Starting child pid: $my_pid runnning tests in directory $test_suite\n";
		$root_name=preg_replace("/\//","-",$this->root);
		$full_name=preg_replace("/\//","-",$test_suite);
		$out_name=preg_replace("/$root_name-/","",$full_name);
			
		$cmd = "$this->php_executable $this->run_tests $test_suite > $out_dir/$out_name.out";
		print "Command: $cmd\n";
		exec($cmd);
		return 1;
	}
	/*
	 * Run the tests in sequence by directory
	 */
	public function run_all_tests_sequential($out_dir) {
		//loop through running all tests

		foreach ($this->test_directory_list as $test_suite){
			print "Runnning tests in directory $test_suite\n";
			$root_name=preg_replace("/\//","-",$this->root);
			$full_name=preg_replace("/\//","-",$test_suite);
			$out_name=preg_replace("/$root_name-/","",$full_name);
				
			$cmd = "$this->php_executable $this->run_tests $test_suite > $out_dir/$out_name.out";
			print "Command: $cmd\n";
			exec($cmd);
		}

	}

	/*
	 * Constructs a list of all of the subdirectories of $root whether they contain tests of not
	 * @param string - name of root directory
	 */
	private function set_directory_list($root) {
		$this->build_directory_list($root, $this->directory_list);
	}
	/*
	 * Returns the full subdirectory list
	 */
	public function get_directory_list() {
		return $this->directory_list;
	}
	/*
	 * Constructs a list of subdirectories which contain test cases
	 */
	public function set_test_directory_list() {
		foreach ($this->directory_list as $dir_name) {
			$dir=dir($dir_name);
			while(( $file=$dir->read()) !==false) {
				if (preg_match("/\w+\.phpt$/", $file)) {
					array_push($this->test_directory_list, $dir_name);
					break;
				}
			}
		}
	}
	/*
	 * Returns the list of subdirectories which contain test cases
	 * $return array - list of subdirectories of root which contain at least one test case.
	 */
	public function get_test_directory_list() {
		return $this->test_directory_list;
	}
	/*
	 * Builds the list of all directories under root
	 * @param string - root directory name
	 * @param array reference - array of directory names
	 */
	private function build_directory_list($thisdir, &$directory_list) {
		$thisdir = dir($thisdir.'/'); //include the trailing slash
		while(($file = $thisdir->read()) !== false) {
			if ($file != '.' && $file != '..') {
				$path = $thisdir->path.$file;
				if(is_dir($path)) {
					array_push($directory_list, $path);
					$this->build_directory_list($path, $directory_list);
				}
			}
		}
		return;
	}
}
?>