<?php
   require_once('_inc.php');
	//选取最大10ip流量
    $SQL = "select logtime, ip, sum(up) as tup ,sum(down) as tdown ,sum(up+down) as total from instraffic 
			where logtime=(select max(logtime) from instraffic)
			and ip<>0 group by ip order by total desc limit 0,10";
			
    $result = $db->query2($SQL,"M",false ); 			
	$res_ip = array(); 
	foreach($result as $row) {
		
		$res_ip[] = array($row['tup'],$row['tdown'],$row['total'],$row['ip']); 
	}   	
	echo json_encode($res_ip);
?>