<?php
  require_once('_inc.php');
  
  
  $page = $_REQUEST['page'];
	$limit= $_REQUEST['rows'];
	$sidx = $_REQUEST['sidx'];
	$sord = $_REQUEST['sord']; 
	
//	$pid = $_REQUEST['p'];
	if(!$sidx) $sidx =1;
	
	
	$sql_coutuser="select count(*) as count from webcat";
	$count=$db->fetchOne($sql_coutuser);

	if( $count >0 ) {
		$total_pages = ceil($count/$limit);
	} else {
		$total_pages = 0;
	}
	if ($page > $total_pages) $page=$total_pages;
	$start = $limit*$page - $limit; // do not put $limit*($page - 1)
	if($start <0) $start = 0;

	$SQL  = "select webid,name from webcat where webid>0 ORDER BY $sidx $sord LIMIT $start , $limit";	
	$result = $db->query2($SQL,"M",false);

	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;

	$i=0;
/*	$SQL1 = "select webinfo from policy where policyid=$pid";

	$webinfo = $db->fetchOne($SQL1);
	$pieces = explode("|",$webinfo);
*/
	
	foreach($result as $row)
	{
	 	$responce->rows[$i]['webid']=$row['webid'];
/*		for($j=0;$j<count($pieces);$j++)
		{
			if(strlen($pieces[$j])==0)
				continue;
			$info = explode(",",$pieces[$j]);
			if($row['webid']==$info[0])
			{
				switch($info[1])
				{
					case 1:
						$log =  "<input type='checkbox' checked='checked' />";
						break;
					case 2:
						$pass = "<input type='checkbox' checked='checked' />";
						break;
					case 3:
						$log =  "<input type='checkbox' checked='checked' />";
						$pass = "<input type='checkbox' checked='checked' />";
						break;
					default:
						break;
				}
			}
		}	*/
	 	$pass = "<input id='wcpass".$row['webid']."' type='checkbox' />";
		$log =  "<input id='wclog".$row['webid']."' type='checkbox' />";

		$responce->rows[$i]['cell']=array($row['webid'],$row['name'],$pass,$log);
		$i++;
	}        
	echo json_encode($responce);  
?>
