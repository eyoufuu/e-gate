<?php
   require_once('_inc.php');

    $format = "select logtime, sum(up) as tup ,sum(down) as tdown ,sum(up+down) as total,proid from instraffic 
			where logtime=(select max(logtime) from instraffic)
	        and ip=%s group by proid  order by total desc limit 0,10";
	$SQL = sprintf($format,	$_REQUEST['ip']);	
//	echo $SQL;
    $result = $db->query2($SQL,"M",false ); 			
	$res_ip = array(); 
	foreach($result as $row) {		
		$res_ip[] = array($row['tup'],$row['tdown'],$row['total'],$row['proid']); 
	}   	
	echo json_encode($res_ip);
?>