<?php
 require_once('_inc.php');
?>

<?php

    $channelname = $_GET['channelid'];
    $modes =  $_REQUEST['modes'];	
    
	$mode = $_REQUEST['mode']; 
	$chanid = $_REQUEST['chanid'];
      
   if(isset($channelname))
    {
	   $SQL = "select * from rules where (channelid=$channelname) and (`mode`='$modes') ";
	   
	}
	
	
	else if(isset($mode))
    {
	   $SQL = "select * from rules where (channelid=" . $chanid.") and (`mode`='$mode') ";
	   
	}
	else
	{
	  $SQL = "select * from rules where `mode`= 5 ";
	}
	
	//echo $SQL;
	$result = $db->query2($SQL,"规则");
	$responce->page = 1;
	$responce->total = 1;
	$i=0;
	
	foreach($result as $row){
		$responce->rows[$i]['id']=$row['id'];
		$proid=$row['value'];
		$sql= "select name from procat where (proid=-1) and (type=$proid)";
	    $result = $db->query2one( $sql);
	    $proname =$result['name'];
        $responce->rows[$i]['cell']=array($row['id'],$row['channelid'],$row['name'],$row['mode'],$proname,$row['description']);
		$i++;
	}  
	$responce->records = $i ;	
	echo json_encode($responce);
?> 




