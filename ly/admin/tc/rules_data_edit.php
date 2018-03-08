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
?>

<?php

	$oper = $_POST['oper'];
	$SQL = "";
	switch($oper)
	{
		case "edit":
		{
		    $ID    = $_POST['id'];
			$pri   = $_POST['priority'];
			$des   = $_POST['description'];
			$SQL = "update rules set priority=$pri,description=$des where id = $ID";

		}
		break;
		
		case "del":
		{
		    $ID = $_POST['id'];
			$SQL = "delete from rules where id in ($ID)";
		}
		break;
	}
	
	$result = $db->query2($SQL,"规则表");
	if($db->get_rows_count()==0)
	{
	   echo json_encode(array('success'=>false,'message'=>"失败:" . mysql_error()));
	}
	else
	{
		echo json_encode(array('succees'=>true,'message'=>"成功"));
	}
?> 




