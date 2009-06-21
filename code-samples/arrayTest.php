<?php

$size = isset($argv[1]) ? $argv[1] : 99999;


// create array

$testArray = array();

for ($i=0; $i<$size; $i++) {
	
	$testArray[$i] = rand(0,9);
}

print "size:\t$size\n";
flush();


// loop

$s = microtime(true);

for ($i=0; $i<$size; $i++) {
	
	if (isset($testArray[$i])) {
	
		if ($i%2 == 0) {
			
			$testArray[$i] = 'G';
		}
	}
}

$e = microtime(true);

$tl = round($e-$s, 5);

print "loop:\t$tl sec (100%)\n";
flush();


// iterator

$s = microtime(true);

$testObject = new ArrayObject($testArray);
$iterator = $testObject->getIterator();

while ($iterator->valid()) {
	
	if ($iterator->key()%2 == 0) {
			
		$testObject[$iterator->current()] = 'G';
	}

	$iterator->next();
}

$e = microtime(true);

$ti = round($e-$s, 5);

$diff = round($ti/$tl*100, 0);

print "spl:\t$ti sec ($diff%)\n";
print "DIFF:\t".($ti-$tl)." sec\n";
flush();


?>