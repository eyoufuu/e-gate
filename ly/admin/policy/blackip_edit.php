<?php
   require_once('_inc.php');
   include("../dbconfig.php");
?>
<?php 
	$rev_oper = $_POST['oper'];
    switch($rev_oper)
    {
    	case 'del':
    		$rev_id = $_POST['id'];
    		$SQL = "delete from specip where id in ($rev_id);";
		
		//	$SQL = sprintf($format,$rev_id);
			$result = $db->query2($SQL,"黑白IP",true);
			$response->msg='删除成功';
			system($gCmd." 6");
    		break;
    	case 'edit':
    		$rev_id = $_POST['bid'];
    	//	$rev_ip = ip2long($_POST['ip']);
			$rev_pass = $_POST['pass'];
		//	echo $rev_mac; 
			$rev_des = $_POST['description'];

			$format = "update specip set pass=%d, description='%s' where id=%d;";
		
			$SQL = sprintf($format,$rev_pass,$rev_des,$rev_id);
			$result = $db->query2($SQL,"黑白IP",true);
			$response->msg='修改成功';
			system($gCmd." 6");
    		break;
    	case 'add':
			$rev_ip = ip2long($_POST['ip']);
			$rev_des = $_POST['description'];
			$rev_pass = $_POST['pass'];
			
			$format = "select count(*) from specip where ip=%u";
			$SQL = sprintf($format,$rev_ip);
			$count = $db->query2_count($SQL,"黑白IP",false);
			if($count == 0)
			{
				$format = "insert into specip (ip,pass,description) values (%u,%d,'%s')";
				$SQL = sprintf($format,$rev_ip,$rev_pass,$rev_des);
				$result = $db->query2($SQL,"黑白IP",true);
				$response->msg='添加成功';
				system($gCmd." 6");				
			}
			else
			{
				$response->msg='数据已经存在';
			}
	    	break;
    	default:    		
    		return;	
    }
    
	echo json_encode($response);
?>