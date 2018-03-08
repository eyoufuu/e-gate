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

	/*$id = $_GET['id'];
	if	(!isset($id))
		exit;
	$SQL  = "select value from rules where id = " . $id;
	
	$result = mysql_query($SQL);
	while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
		
	}*/
	
	$table = "procat";
	$SQL = "select * from " . $table ." where proid = -1";
	$result = mysql_query($SQL) or die("Invalid query: " . mysql_error());
	
	$all = array();
	
	while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
	    $responce[$row[type]]=array("name"=>$row[name],"type"=>$row[type],"des"=>$row[description],"channelname"=>$row[channelname],"lr"=>"l"); 
	}        
	echo json_encode($responce);
	
	mysql_close($db_1);
?> 