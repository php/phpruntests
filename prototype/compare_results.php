<?php
/*
 * This compares the results of running a set of tests in sequence with those from running teh same
 * set of tsts in parallel. 
 */
class CompareResults {
	public function compare($test_list, $loc1, $loc2, $root) {
		$root_name=preg_replace("/\//","-",$root);
		foreach($test_list as $test_suite) {

			$full_name=preg_replace("/\//","-",$test_suite);
			$out_name=preg_replace("/$root_name-/","",$full_name);


			$string1=file_get_contents($loc1."/".$out_name.".out");
			$string2=file_get_contents($loc2."/".$out_name.".out");
				
			$check_strings = array (
			"Tests passed",
			"Tests skipped",
			"Tests warned",
			"Tests failed",
			"Expected fail",
			);
			
			$same = true;
				
			foreach($check_strings as $cs) {
				$a = CompareResults::get_number($cs, $string1);
				$b = CompareResults::get_number($cs, $string2);
				if ($a != $b) {
					echo "Comparing filename : $out_name parallel $cs= $a sequential $cs =$b\n";
					$same=false;
				}
			}
			if($same) {
				echo "File OK: $out_name\n";
			}else {
				echo "compare failed: $out_name\n";
			}
		}
	}
	public function get_number($findstring, $string) {
		$num = null;
		if( preg_match("/$findstring\s+:\s+(\d+)/",$string,$matches)) {
			$num=$matches[1];
		}
		return $num;
	}
}
?>