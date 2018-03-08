<?php
  require_once('_inc.php');
  
 //$qos = $_REQUEST['qos'];
  $p2p = $_REQUEST['p2p'];
  
$SQL = "update globalpara set stc_p2p=$p2p";
 // $SQL = "update globalpara set isqosopen=0";
 //echo $SQL; 
 $db->query2($SQL);
 @system("/usr/bin/sudo /home/lytc");


?>