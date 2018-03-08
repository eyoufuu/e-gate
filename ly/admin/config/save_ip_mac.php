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

//	$db_1 = mysql_connect($dbhost, $dbuser, $dbpassword)
//	or die("连接错误: " . mysql_error());

//	mysql_select_db($database) or die("Error conecting to db.");
   
	$rev_ip = explode(",",$_POST['ip']);
//	echo $rev_ip;
	$rev_mac = explode(",",$_POST['mac']);
//	echo $rev_mac; 
	$rev_memo = explode(",",$_POST['memo']);
//	echo $rev_memo; 

	$num = count($rev_ip);
$j=0;
$format = "INSERT INTO ipmac (ip,mac,bind,memo) values(%u,'%s',1,'%s') on duplicate key update mac='%s',memo='%s';";
	for($i=0;$i<$num;$i++)
	{
		$SQL = sprintf($format,ip2long($rev_ip[$i]),$rev_mac[$i],$rev_memo[$i],$rev_mac[$i],$rev_memo[$i]);
		$result = $db->query2($SQL,"M",false);
		$j++;

	}	
//	$count = mysql_affected_rows();
	$result = $db->query2("select isipmacbind from globalpara","全局设置",true);
  	$ipmac = $result['0']['isipmacbind'];
	switch($ipmac) 
		{
			case "0": 
			case "1":
				   break;
			case "2":
			case "3":
				$result = $db->query2("SELECT ip,mac from ipmac where bind=1","M",false);
				foreach($result as $row)
				{				
					$cmd = "/usr/bin/sudo echo ".$row['ip']." " .$row['mac']." > /proc/bridge_ipmac";				
					system($cmd);
				}
				break;		
			default:
				break;
		}

	$response->ip=$j.'条数据更新成功';

//	mysql_close($db_1);
	echo json_encode($response);
/*	$table = "ipmac";//
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
	echo json_encode($response);*/
?> 




