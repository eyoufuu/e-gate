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

	$table = "simpletractl";//默认是tc控制表，哈哈
	$oper = $_POST['oper'];
	
	$SQL = "";
	switch($oper)
	{
	//以下必须自行修改
		case "edit":
		{
		    $ID = $_POST['id'];
			$ips = $_POST['ips'];
			$ipe = $_POST['ipe'];
			$upbw = $_POST['upbw'];
			$downbw  = $_POST['downbw'];
			$SQL = "update simpletractl set ips='$ips',ipe='$ipe',upbw= $upbw, downbw=$downbw where id = $ID";

		}
		break;
		
		case "del":
		{
		    $ID = $_POST['id'];
			$SQL = "delete from simpletractl where id in ($ID)";
		}
		break;
		case "add":
		{
			//$ID = $_POST['id'];
			$ips = $_POST['ips'];
			$ipe = $_POST['ipe'];
			$upbw = $_POST['upbw'];
			$downbw  = $_POST['downbw'];
			$SQL = "insert into channel(ips,ipe,upbw,downbw) values('$ips','$ipe',$upbw,$downbw)";
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




