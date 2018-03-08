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
	$table = "file_pass";//默认是tc控制表，哈哈

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

	//$SQL  = "select u.*,v.name as filetypename from (SELECT * FROM file_pass) as u left join (select * from filecat) as v on u.filetype=v.typeid  ORDER BY $sidx $sord LIMIT $start , $limit";	
	$SQL="SELECT * FROM file_pass";
	$result = $db->query2($SQL);
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i=0;
	foreach($result as $row){
		$responce->rows[$i]['id']=$row[id];
		if($row[ftp] == 0)
		  $ftp = "<input type = checkbox  id = '" .$row['id'] . "ftp' />";
		else
		  $ftp = "<input type = checkbox checked id = '" .$row['id'] . "ftp' />";
		 
		if($row[tftp] == 0)
		 $tftp =  "<input type = checkbox  id = '" .$row['id'] . "tftp' />";
		else
		 $tftp = "<input type = checkbox checked id = '" .$row['id'] . "tftp' />";
 
		$responce->rows[$i]['cell']=array($row[id],$row[ips],$row[ipe],$row[mail],$row[im],$row[netdisk],$row[bbs],$ftp,$tftp);
		$i++;
	}
	echo json_encode($responce);
?> 




