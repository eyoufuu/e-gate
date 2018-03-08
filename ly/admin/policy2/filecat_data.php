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
	
	$ipid = $_GET['ipids']; 
	$page = $_REQUEST['page'];
	$limit= $_REQUEST['rows'];
	$sidx = $_REQUEST['sidx'];
	$sord = $_REQUEST['sord']; 
	if(!$sidx) $sidx =1;
	$table = "file_transter";//默认是tc控制表，哈哈

	$SQL = "SELECT COUNT(*) AS count FROM " . $table." where function='文件类型'";
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
	$SQL ="select * from file_transter where function='文件类型'  ORDER BY $sidx $sord LIMIT $start,$limit";
	$result = $db->query2($SQL);
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	if(isset($ipid))
	{
	  $SQL2 ="select filetype from file_pass where id=$ipid";
	  $filetypes= $db->query2one($SQL2); 
      $filetypech=explode(',',$filetypes['filetype']);
   	}
	 $i=0;
	foreach($result as $row){
		$responce->rows[$i]['id']=$row[id];
		//$row[typeid]
		//$keycheck=explode(',',$t);
        if($filetypech[$i] == "1")   		
		 $box = "<input type = checkbox checked id = '" .$row[id]. "filetype' />"; 
		else
		 $box = "<input type = checkbox  id = '" .$row[id]. "filetype' />"; 
		$responce->rows[$i]['cell']=array($row[id],$row[key],$row[address],$box);
		$i++;
	 }
	
	echo json_encode($responce);
?> 




