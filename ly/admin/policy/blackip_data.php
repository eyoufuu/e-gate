<?php
   require_once('_inc.php');
?>
<?php
	$page = $_REQUEST['page'];
	$limit= $_REQUEST['rows'];
	$sidx = $_REQUEST['sidx'];
	$sord = $_REQUEST['sord']; 
	if(!$sidx) $sidx =1;
	
	
	$sql_coutuser= "select count(*) as count from specip";	
	$count=$db->query2_count($sql_coutuser);

	if( $count >0 ) {
		$total_pages = ceil($count/$limit);
	} else {
		$total_pages = 0;
	}
	if ($page > $total_pages) $page=$total_pages;
	$start = $limit*$page - $limit; // do not put $limit*($page - 1)
	if($start <0) $start = 0;

	$SQL  = "select * from specip ORDER BY $sidx $sord LIMIT $start , $limit";	
	$result = $db->query2($SQL,"黑白IP名单",true);
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;

	$i=0;
	foreach($result as $row) {
		$responce->rows[$i]['id']=$row['id'];
		if($row['pass']==0)
			$pass='阻挡';
		else
			$pass='放行';
		$responce->rows[$i]['cell']=array($row['id'],long2ip($row['ip']),$pass,$row['description']);
		$i++;
	}        
	echo json_encode($responce);
?> 




