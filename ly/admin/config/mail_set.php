<?php 
require_once ('_inc.php');
 
 	$isopen = $_POST['remind'];
 	if($isopen == 1)
 	{
	 	$server = $_POST['server'];
	 	$from= $_POST['from'];
	 	$pwd= $_POST['pwd'];
	 	$to = $_POST['to'];
	 	$db->query("delete from smtpserver"); 			
	 	$format = "insert into smtpserver  values('%d','%s','%s','%s','%s')";
	 	$sql = sprintf($format,$isopen,$server,$from,$pwd,$to);
 	}
 	else
 	{
 		$sql = "update smtpserver set isopen=0 where isopen>=0";	
 	}
 echo $sql;
 $arr = $db->query($sql); 
?>