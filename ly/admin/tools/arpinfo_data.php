<?php 
 	require_once('_inc.php');
 	$return_array = array();
 	unset($return_array); 
	@exec("/usr/bin/sudo dmesg -c |grep '^__arp'", $return_array);

//	$info =array();
//	$return_array = array("hello","good");
 //	$info = array("msg"=>$return_array);
	echo json_encode($return_array);
?>
