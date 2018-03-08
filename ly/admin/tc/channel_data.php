<?php
  require_once('_inc.php');
?>

<?php
	$page = $_REQUEST['page'];
	$limit= $_REQUEST['rows'];
	$sidx = $_REQUEST['sidx'];
	$sord = $_REQUEST['sord']; 
	
	if(!$sidx) $sidx =1;
	$table = "channel";
    $SQL = "SELECT COUNT(*) AS count FROM " .$table ; 	
	$count = $db->query2_count($SQL);
	if( $count >0 ) {
		$total_pages = ceil($count/$limit);
	} else {
		$total_pages = 0;
	}
	if ($page > $total_pages) $page=$total_pages;
	$start = $limit*$page - $limit; // do not put $limit*($page - 1)
	if($start <0) $start = 0;
	$SQL  = "SELECT * FROM channel ORDER BY $sidx $sord LIMIT $start , $limit";	
	$result = $db->query2($SQL,"通道表");
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i=0;
	foreach($result as $row){
		$responce->rows[$i]['id']=$row['id'];
		if($row['priority'] == 1)
		  $prio ="高";
		else if($row['priority'] == 2)
		$prio ="中";
		else if($row['priority'] == 3)
		$prio ="低";
		
		
		$responce->rows[$i]['cell']=array($row['id'],$row['name'],$row['uprate']/8,$row['downrate']/8,$prio);
		$i++;
	}
	echo json_encode($responce);
?> 




