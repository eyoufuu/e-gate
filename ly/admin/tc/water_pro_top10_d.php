<?php
   require_once('_inc.php');
//   $db_tc = mysql_connect($dbhost, $dbuser, $dbpassword)
//	or die("不能连接: " . mysql_error());


/*	mysql_select_db($database) or die("选择数据库错误.");
    $SQL = "select count(*) as count from procat_mem";
	$result = mysql_query( $SQL ) or die("数据库查询错误.".mysql_error());
	$row = mysql_fetch_array($result,MYSQL_ASSOC);
	$count = $row['count'];
	if($count==0)
	{
		$SQL = "INSERT INTO procat_mem SELECT `proid`,`name`,`type` FROM procat ";
		mysql_query( $SQL )  or die("插入内存表错误" . mysql_error());
	}*/
	//选取最大10协议流量
	$SQL = "select logtime, sum(up) as tup ,sum(down) as tdown ,sum(up+down) as total,proid from instraffic 
			where logtime=(select max(logtime) from instraffic)
	        and ip<>0 group by proid  order by total desc limit 0,10";

	$result = $db->query2($SQL,"M",false);		
	$res_pro = array();
	foreach($result as $row ) {
		$res_pro[] = array($row['tup'],$row['tdown'],$row['total'],$row['proid']); 
	}   	
	echo json_encode($res_pro);
	
	//选取最大10ip流量
/*    $SQL = "select logtime, ip, sum(up) as tup ,sum(down) as tdown ,sum(up+down) as total from instraffic 
			where logtime=(select max(logtime) from instraffic)
			and ip<>0 group by ip order by total desc limit 0,10";
	$result = mysql_query( $SQL ) or die("数据库查询错误.".mysql_error());
	$res_ip = array(); 
	while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
		//$res['data'][$i]=array($i,$row['up'],$row['down']);
		$res_ip[] = array($row['tup'],$row['tdown'],$row['total'],$row['ip']); 
	}   	
    $t = array();
    $t = array('pro'=>$res_pro,'ip'=>$res_ip);	

  echo json_encode($t);
*/
?>