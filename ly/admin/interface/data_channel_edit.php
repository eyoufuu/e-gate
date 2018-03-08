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

	$table = "channel";
	$oper = $_POST['oper'];
	
	$SQL = "";
	switch($oper)
	{
		case "edit":
		{
		    $ID = $_POST['id'];
			$rateup = $_POST['rateup'];
			$ratedown = $_POST['ratedown'];
			$name = $_POST['name'];
			$pri  = $_POST['priority'];
			$SQL = "update channel set rateup=$rateup,ratedown=$ratedown,name= '$name', priority=$pri where id = $ID";

		}
		break;
		
		case "del":
		{
		    $ID = $_POST['id'];
			$SQL = "delete from channel where id in ($ID)";
		}
		break;
		case "add":
		{
			$rateup = $_POST['rateup'];
			$ratedown = $_POST['ratedown'];
			$name = $_POST['name'];
			$pri  = $_POST['priority'];
			$SQL = "insert into channel(name,rateup,ratedown,priority) values('$name',$rateup,$ratedown,$pri)";
		}
		break;
		
	}
	$result = mysql_query( $SQL ); //or
	//{
	//    echo json_encode(array('success'=>false,'message'=>mysql_error()));
    	//die("不能执行.".mysql_error());
	//}
	if(mysql_affected_rows()==0)
	{
	   echo json_encode(array('success'=>false,'message'=>"失败" . mysql_error()));
	}
	echo json_encode(array('succees'=>true,'message'=>"成功"));
	mysql_close($db_1);
?> 




