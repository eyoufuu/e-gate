<?php
   require_once('_inc.php');
   $SQL = "SELECT logtime,up,down from instraffic where ip=0 ";
   //time(FROM_UNIXTIME(logtime))as 
   $result = $db->query2($SQL,"M",false);
   $i =0;
   foreach($result as $row) {
		//$res['data'][$i]=array($i,$row['up'],$row['down']);		
		$res[$i] = array('time'=>$row['logtime'],'up'=>$row['up'],'down'=>$row['down']); 
		$i++;
   }   	
   
   echo json_encode($res);
?>