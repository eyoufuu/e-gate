<?php
/*
   * Modified: 2010-3-2
   * By: qianbo (qianbo@chd.edu.cn)
   *
   * Created: 2010-3-1
   * By: qianbo (qianbo@chd.edu.cn)
   *
*/ 
   require_once('_inc.php');
   include("../dbconfig.php");
?>

<?php

	$db_1 = mysql_connect($dbhost, $dbuser, $dbpassword)
	or die("连接错误: " . mysql_error());


	mysql_select_db("baseconfig") or die("Error conecting to db.");

	$page = $_REQUEST['page'];
	$limit= $_REQUEST['rows'];
	$sidx = $_REQUEST['sidx'];
	$sord = $_REQUEST['sord']; 
	if(!$sidx) $sidx =1;
	
	$table = "ipmac";//默认是tc控制表，哈哈

	
	$result = mysql_query("SELECT COUNT(*) AS count FROM " . $table);
	$row = mysql_fetch_array($result,MYSQL_ASSOC);
	$count = $row['count'];

	if( $count >0 ) {
		$total_pages = ceil($count/$limit);
	} else {
		$total_pages = 0;
	}
	if ($page > $total_pages) $page=$total_pages;
	$start = $limit*$page - $limit; // do not put $limit*($page - 1)
	if($start <0) $start = 0;

	$SQL  = "SELECT ip,mac,bind,memo FROM " . $table . " ORDER BY $sidx $sord LIMIT $start , $limit";	
	$result = mysql_query( $SQL ) or die("不能执行.".mysql_error());

	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	
	//$SQL  = "select id,ips,ipe,upbw,downbw from simpletractl";
	//"select `proid`,`name`,`type`,`description` from procat where `proid`=-1 order by `type` , `proid` ";
	//$result = mysql_query( $SQL ) or die("不能执行该语句.".mysql_error());
	$i =0;
	while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
		$responce->rows[$i]['ip']=$row[ip];
		$responce->rows[$i]['cell']=array($row[ip],$row[mac],$row[bind],$row[memo]);
		$i++;
	}        
	echo json_encode($responce);
?> 




