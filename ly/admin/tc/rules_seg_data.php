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

   $channelname = $_GET['channelid'];
	$SQL = "";
	if(isset($channelname))
    {
		$SQL = "select * from rules where (channelid=" . $channelname) and (mode=2)  ;
	}
	else
	{
		$SQL = "select * from rules";
	}
	
	$result = $db->query2($SQL,"规则");
	$responce->page = 1;
	$responce->total = 1;
	$i=0;
	foreach($result as $row){
		$responce->rows[$i]['id']=$row['id'];
		$responce->rows[$i]['cell']=array($row['id'],$row['channelid'],$row['name'],$row['mode'],$row['value'],$row['description']);
		$i++;
	}  
	$responce->records = $i ;	
	echo json_encode($responce);
?> 




