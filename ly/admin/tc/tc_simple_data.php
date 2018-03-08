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
	$page = $_REQUEST['page'];
	$limit= $_REQUEST['rows'];
	$sidx = $_REQUEST['sidx'];
	$sord = $_REQUEST['sord']; 
	if(!$sidx) $sidx =1;
	$table = "simpletractl";//默认是tc控制表，哈哈

	$SQL = "SELECT COUNT(*) AS count FROM " . $table;
	$count = $db->query2_count($SQL);

	if( $count >0 ) 
	{
		$total_pages = ceil($count/$limit);
	} 
	else 
	{
		$total_pages = 0;
	}
	if ($page > $total_pages) $page=$total_pages;
	$start = $limit*$page - $limit; // do not put $limit*($page - 1)
	if($start <0) $start = 0;

	$SQL  = "SELECT * FROM simpletractl ORDER BY $sidx $sord LIMIT $start , $limit";	
	$result = $db->query2($SQL,"简单流控");
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i=0;
	foreach($result as $row){
		$responce->rows[$i]['id']=$row[id];
		$responce->rows[$i]['cell']=array($row[id],$row[ips],$row[ipe],$row[upbw]/8,$row[downbw]/8);
		$i++;
	}
	echo json_encode($responce);
?> 




