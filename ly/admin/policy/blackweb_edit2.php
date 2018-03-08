<?php
   require_once('_inc.php');
   include("../dbconfig.php");
?>
<?php 
	$db_1 = mysql_connect($dbhost, $dbuser, $dbpassword)
	or die("连接错误: " . mysql_error());

	mysql_select_db($database) or die("Error conecting to db.");
    $rev_oper = $_POST['oper'];
    switch($rev_oper)
    {
    	case 'del':
    		$rev_id = $_POST['id'];
    		$SQL  = "delete from specweb where id in ($rev_id)";
		
		//	$SQL = sprintf($format,$rev_id);
			$result = $db->query2($SQL,"黑白网址",true);
			system($gCmd." 7");
    		break;
    	case 'edit':
    		$rev_id = $_POST['bid'];
		//	echo $rev_ip;
			$rev_host = $_POST['host'];
		//	echo $rev_mac; 
			$rev_des = $_POST['description'];
		//	echo $rev_memo; 
			$rev_pass = $_POST['pass'];

			$format = "update specweb set host='%s',pass='%d', description='%s' where id=%d;";
		
			$SQL = sprintf($format,$rev_host,$rev_pass,$rev_des,$rev_id);
			$result = $db->query2($SQL,"黑白网址",true);
			system($gCmd." 7");
    		break;
    	case 'add':
    	//	$rev_id = $_POST['bid'];
			$rev_host = $_POST['host'];
			$rev_des = $_POST['description'];
			$rev_pass = $_POST['pass'];
			$SQL = "select count(*) from specweb where host='$rev_host';";
			$count = $db->query2_count($SQL,"黑白网址",true);
		
			if($count==0)
			{
				$SQL = "insert into specweb(`host`,`pass`,`description`) values ('$rev_host',$rev_pass,'$rev_des')";
				$result = $db->query2($SQL,"黑白网址",true);
				system($gCmd." 7");
			}
			else
			{
					$response->msg='数据已存在';					
			}
    		break;
    	default:    		
    		return;	
    }    
	$response->msg='保存成功';
	echo json_encode($response);
?>