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
	
	$ip = $_GET['ip_add'];
	$mac= $_GET['mac_add'];
	$memo = $_GET['memo_add'];
	$table = "ipmac";//
    $SQL = "SELECT ip, mac FROM " . $table ." where ip = '$ip' or mac= '$mac' ";
	$result = mysql_query($SQL);
	$row = mysql_fetch_array($result,MYSQL_ASSOC);
	$count = mysql_affected_rows();
	if($count>0)
	{
	   $Response->res = 0;
	   $Response->res_s = "ip 或者mac 已经存在!";
	   $Response->ip= $row['ip'];
       $Response->mac= $row['mac'];	   
	   echo json_encode($Response); 
	}
	else
	{
	   $SQL = "insert into $table(ip,mac,bind,memo) values('$ip','$mac',1,'$memo')";
	   $result = mysql_query($SQL);
	   $Response->res = 1;
	   $Response->res_s = "保存成功!";
	   $Response->ip= $ip;
       $Response->mac= $mac;	   
	   echo json_encode($Response);
	   
	}
	
?>