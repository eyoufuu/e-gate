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


	mysql_select_db($database) or die("Error conecting to db.");

	$table = "channel";
	$SQL = "select id,name from " . $table;
	$result = mysql_query($SQL);
	$responce->count = mysql_affected_rows($db_1);
	$i=0;
	while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
		$responce->id[$i]=$row[id];
		$responce->name[$i]=$row[name];
		$i++;
	}        

	echo json_encode($responce);
	mysql_close($db_1);
?> 




