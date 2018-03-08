<?php
   require_once('_inc.php');
   	$page = $_REQUEST['page'];
	$limit= $_REQUEST['rows'];
	$sidx = $_REQUEST['sidx'];
	$sord = $_REQUEST['sord']; 
	if(!$sidx) $sidx =1;
   
   date_default_timezone_set('Asia/Shanghai');
   $date_ym = date('Ym');
   $date_day = date('d');
 
   $tablename=$date_ym."flowdata";						
   
   $SQL = "select count(distinct(ip_inner)) from " . $tablename; 
   $count = $db->query2_count($SQL,"M",false);
   
   	if( $count >0 ) {
		$total_pages = ceil($count/$limit);
	} else {
		$total_pages = 0;
	}
	if ($page > $total_pages) $page=$total_pages;
	$start = $limit*$page - $limit; // do not put $limit*($page - 1)
	if($start <0) $start = 0;
   
   $SQL = "select ip_inner,sum(upflow) as up,sum(downflow) as down,sum(upflow)+sum(downflow) as total from ". $tablename . "where day(FROM_UNIXTIME(logtime))=$date_day group by ip_inner order by total desc LIMIT $start , $limit ";
 //  echo $SQL;
   $result = $db->query2($SQL,"当天流量查看",true);
   $i =0;
   foreach($result as $row) {
   		$responce->rows[$i]['id']=$i;
		$responce->rows[$i]['cell']=array(long2ip($row['ip_inner']),$row['up'],$row['down'],$row['total']);
		$i++;
   }
   echo json_encode($responce);
?>