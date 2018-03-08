<?php
  require_once('_inc.php');
?>

<?php
	$table = "channel";
	$oper = $_POST['oper'];
	$SQL = "";
	switch($oper)
	{
		case "edit":
		{
		    $ID = $_POST['id'];
			$rateup = $_POST['uprate'];
			$ratedown = $_POST['downrate'];
			$name = $_POST['name'];
			$pri  = $_POST['priority'];
			$SQL = "update channel set uprate=$rateup*8,downrate=$ratedown*8,name= '$name', priority=$pri where id = $ID";
              
		}
		break;
		
		case "del":
		{
		    $ID = $_POST['id'];
			$SQL = "delete from channel where id in ($ID)";
			$sqlrules ="delete from rules where channelid in ($ID)";
			$db->exec( $sqlrules);
	
		}
		break;
		case "add":
		{
			$rateup = $_POST['uprate'];
			$ratedown = $_POST['downrate'];
			$name = $_POST['name'];
			$pri  = $_POST['priority'];
			$SQL = "insert into channel(name,uprate,downrate,priority) values('$name',$rateup*8,$ratedown*8,$pri)";
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




