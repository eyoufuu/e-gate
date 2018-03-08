<?php
   require_once('_inc.php');
	//选取最大10ip流量
	
   date_default_timezone_set('Asia/Shanghai');
   $date_ym = date('Ym');
   $date_day = date('d');
   $tablename=$date_ym."flowdata";	

   $SQL = "select hour(FROM_UNIXTIME(logtime)) as hm,sum(upflow) as up,sum(downflow) as down from " . $tablename . " where day(FROM_UNIXTIME(logtime))= day(now()) group by  hm"; 
//   declare date2 varchar(20);
//   declare date_true varchar(300);  
//   declare tablename varchar(300);
//   set date2 = concat(EXTRACT(YEAR_MONTH FROM  now()),'flowdata');
//   set @tablename = concat('',date2,'); 
//数据结构	
 //  $SQL ="call test1;";
			
    $result = $db->query2($SQL,"M",false ); 			
	$res_24hour = array(); 
	

	
    $res_up =  array(0,0,0,0,0,0,0,0,0,0,0,0);
    $res_down =array(0,0,0,0,0,0,0,0,0,0,0,0);
	
	
	foreach($result as $row) {
	       $t = floor($row['hm']/2);//每2小时1个节点
		   $res_up[$t] += $row['up'];
		   $res_down[$t] += $row['down'];
	}
    for($i = 0;$i<12;$i++)
    {
	    $res_24hour[] = array($i,$res_up[$i],$res_down[$i]);
    } 	
   	
	echo json_encode($res_24hour);
?>