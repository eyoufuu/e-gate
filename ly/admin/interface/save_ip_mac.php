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

	$ip = $_GET['ip'];
	$mac= $_GET['mac'];
	$memo = $_GET['memo'];
	$table = "ipmac";//
    $SQL = "SELECT ip, mac FROM " . $table ." where ip<>'$ip' and mac= '$mac' ";
	$result = mysql_query($SQL);
	$row = mysql_fetch_array($result,MYSQL_ASSOC);
	$count = mysql_affected_rows();
	
	//$`=$row['ip'];
	//
	if($count==0) //没有该数据
	{
	   $response->res=1; 
	   $responce->return_s = "保存成功";
       $response->ip=$ip;
	   $response->mac=$mac;
	   $response->memo=$memo;
	   $SQL = "update ipmac set mac='$mac' ,memo='$memo' where ip = '$ip'";
	   mysql_free_result($result);
	   $result = mysql_query($SQL);
	}
	else
	{
	   $get_ip = $row[ip];
	   $response->res=0;
   	   mysql_free_result($result);
	   $result  = mysql_query("SELECT ip, mac,memo FROM " . $table . " where ip = '$ip' ");
	   $row = mysql_fetch_array($result,MYSQL_ASSOC);
	   $count = mysql_affected_rows();
    	if($count=1)
		{
			$response->return_s = "保存失败,与IP地址为$get_ip 的mac 冲突!";	
			$response->ip=$row['ip'];
			$response->mac=$row['mac'];
			$response->memo=$row['memo'];
		}
		else
		{
		    $response->return_s= "保存失败,且无法得到原先IP与MAC地址和备注!";
			$response->ip="";
			$response->mac="";
			$response->memo="";
		}
		mysql_free_result($result);
	}
	mysql_close($db_1);
	echo json_encode($response);
	
?> 




