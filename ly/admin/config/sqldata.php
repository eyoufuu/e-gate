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

	$db_1 = mysql_connect($dbhost, $dbuser, $dbpassword) or die("连接错误: " . mysql_error());
	mysql_select_db($database) or die("Error conecting to db.");
	$page = $_REQUEST['page'];
	$limit= $_REQUEST['rows'];
	$sidx = $_REQUEST['sidx'];
	$sord = $_REQUEST['sord']; 
	if(!$sidx) $sidx =1;
	$table = "ipmac";	
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

	$SQL  = "SELECT * FROM ipmac ORDER BY $sidx $sord LIMIT $start , $limit";	
	$result = mysql_query( $SQL ) or die("不能执行.".mysql_error());

	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i = 0;
	while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
			$responce->rows[$i]['id']=$row['ip'];
		//		if($row['bind']==0)
		//	   $checkbox_bind = "<input type=checkbox id = '$i' value = '0' />";
		//	else
        //     $checkbox_bind = "<input type=checkbox checked id = '$i' value = '1' />";
			$responce->rows[$i]['cell']=array(long2ip($row['ip']),$row[mac],$row[memo],$row['bind']?'是':'否');
			$i++;
		}
	echo json_encode($responce);
	mysql_close($db_1);
?> 




