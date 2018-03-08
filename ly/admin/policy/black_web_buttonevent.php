<?php
/*
   * 用来处理黑白名单表格里面的按钮消息（jgrid自带的按钮）
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
			$SQL = "delete from specweb where id in ($ID)";
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




