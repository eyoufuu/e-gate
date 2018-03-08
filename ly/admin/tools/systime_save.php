<?php 
 require_once('_inc.php');
	$date = $_REQUEST['d'];
	$time = $_REQUEST['t'];
	@exec("/usr/bin/sudo date -s ".$date);
	@exec("/usr/bin/sudo date -s ".$time);
	@exec("/usr/bin/sudo clock -w ");
?>