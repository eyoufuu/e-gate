<?php
  require_once('_inc.php');
  
  
  $lend = $_REQUEST['lend'];
  $icmp = $_REQUEST['icmp'];  
  $ack = $_REQUEST['ack'];  
 
  $SQL = "update globalpara set ceil=$lend,icmp=$icmp,ack=$ack";
 // $SQL = "update globalpara set isqosopen=0";
 //echo $SQL; 
 $db->query2($SQL);
	exec("/usr/bin/sudo /home/lytc");
?>