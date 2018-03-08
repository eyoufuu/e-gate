
<?php
    
	require_once('_inc.php');
	function test_ip_eq_from_tc($id,$proid)
	{
	    $SQL = "select count(*) from rules where value='". $proid . "' and id<>".$id ;
		global $db;
		$res = $db->fetchOne($SQL);
		if($res == "0")
		   return true;
		else
		   return false;
	}
	
	$table = "rules";
	$oper = $_POST['oper'];
	$SQL = "";
	switch($oper)
	{
		case "edit":
		{   
            $id = $_POST['id'];		
		    $proid = $_POST['pro'];
			if(!test_ip_eq_from_tc($id,$proid))
				{
					echo json_encode(array('success'=>false,'message'=>"失败,该协议类别已经存在与某个通道，可选择修改"));
					return false;
				}
			$des  = $_POST['des'];
			$SQL = "update rules set value='$proid',description='$des'  where id=$id";
        }
		break;
		
		case "del":
		{
		    $ID = $_POST['id'];
			$SQL = "delete from  rules where id in ($ID)";
	
		}
		break;
		case "add":
		{     
			$proid = $_POST['pro'];
			if(!test_ip_eq_from_tc("-1",$proid))
				{
					echo json_encode(array('success'=>false,'message'=>"失败,该协议类别已经存在与某个通道，可选择修改"));
					return false;
				}
			$cid = $_POST['id'];
			$des = $_POST['des'];
			$sql= "select name from channel where id=$cid";
			$result = $db->query2one( $sql);
			$cname =$result['name'];
			$mode="2";
			$SQL = "insert into rules(name,mode,value,description,channelid) values('$cname','$mode','$proid','$des',$cid)";
		}
		break;
		
	}
	
	$result = $db->query2( $SQL,"高级流控" ); //or
	if($db->get_rows_count()==0)
	{
	   echo json_encode(array('success'=>false,'message'=>"失败" . mysql_error()));
	}
	else
	{
		echo json_encode(array('succees'=>true,'message'=>"成功"));
	}
?> 




