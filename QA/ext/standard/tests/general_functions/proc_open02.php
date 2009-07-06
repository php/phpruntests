<?php
$ds = array(array('pipe', 'r'));

$cat = proc_open(
	'/usr/bin/nohup /bin/sleep 50',
	$ds,
	$pipes
);

sleep(1); // let the OS run the nohup process before sending the signal

var_dump(proc_terminate($cat, 1)); // send a SIGHUP
sleep(1);
var_dump(proc_get_status($cat));

var_dump(proc_terminate($cat)); // now really quit it
sleep(1);
var_dump(proc_get_status($cat));

proc_close($cat);

echo "Done!\n";

?>
