<?php
   error_reporting(E_ALL);

  // require_once('_inc.php');
   include("../dbconfig.php");
   $db_tc = mysql_connect($dbhost, $dbuser, $dbpassword)
	or die("不能连接: " . mysql_error());


	mysql_select_db($database) or die("选择数据库错误.");
    
	
	$SQL = "SELECT logtime,up,down from instraffic where ip=0  order by logtime Desc limit 0,1 ";
	$result = mysql_query( $SQL ) or die("数据库查询错误.".mysql_error());
	$res = array(); 
	//$res['label'] = '流量';
	$i =0;
	//$res['data']=array();
	while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
		//$res['data'][$i]=array($i,$row['up'],$row['down']);
		$res[$i] = array('up'=>$row['up'],'down'=>$row['down']); 
		$i++;
	}   	
   	mysql_close($db_tc); 
	echo json_encode($res);
    unset($res);

?>