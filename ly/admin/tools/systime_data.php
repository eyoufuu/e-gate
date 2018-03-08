<?php 
 	require_once('_inc.php');
 //	$return_array = array();
 //	unset($return_array); 
	//@exec("date +'%Y-%m-%d %H:%M:%S %A'", $return_array);
	date_default_timezone_set('Asia/Shanghai');
	$return = date("Y-m-d H:i:s N");
	echo json_encode($return);
?>