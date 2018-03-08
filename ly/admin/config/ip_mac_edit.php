<?php
   require_once('_inc.php');
   include("../dbconfig.php");
?>
<?php 
//	$db_1 = mysql_connect($dbhost, $dbuser, $dbpassword)
//	or die("连接错误: " . mysql_error());

	$result = $db->query2("select isipmacbind from globalpara","全局设置",true);
  	$ipmac = $result['0']['isipmacbind'];
	 switch($ipmac) 
    {
	   	case "0": 
		case "1":
			   break;
		case "2":
		case "3":
			$result = $db->query2("SELECT ip from ipmac","M",false);
			foreach($result as $row)
			{
				$cmd = "/usr/bin/sudo echo ".$row['ip']." " ."FF:FF:FF:FF:FF:FF"." > /proc/bridge_ipmac";
				system($cmd);
			}
			break;			
		default:
			break;
    }
//	mysql_select_db($database) or die("Error conecting to db.");
    $rev_oper = $_POST['oper'];
    switch($rev_oper)
    {
    	case 'del':
    		$rev_ip = $_POST['id'];
    		$SQL = "delete from ipmac where ip in ($rev_ip);";
			
		//	$SQL = sprintf($format,ip2long($rev_ip));
    		break;
    	case 'edit':
    		$rev_ip = $_POST['ip'];
		//	echo $rev_ip;
			$rev_mac = $_POST['mac'];
		//	echo $rev_mac; 
			$rev_memo = $_POST['memo'];
		//	echo $rev_memo; 
			$rev_bind = $_POST['select'];

			$format = "update ipmac set mac='%s',memo='%s',bind=%d where ip=%u;";
		
			$SQL = sprintf($format,$rev_mac,$rev_memo,$rev_bind,ip2long($rev_ip));
    		break;
		case 'add':
			$rev_ip = ip2long($_POST['ip']);
		//	echo $rev_ip;
			$rev_mac = $_POST['mac'];
		//	echo $rev_mac; 
			$rev_memo = $_POST['memo'];
		//	echo $rev_memo; 
			$rev_bind = $_POST['select'];

			$format = "insert into ipmac (ip,mac,bind,memo) values(%u,'%s',%d,'%s')";
		
			$SQL = sprintf($format,$rev_ip,$rev_mac,$rev_bind,$rev_memo);
			break;
    		
    }	
	//mysql_query($SQL);
	$db->query2($SQL,"M",false);
	
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
	$response->ip='数据保存成功';

//	mysql_close($db_1);
	echo json_encode($response);
?>