
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
	$page = $_REQUEST['page'];
	$limit= $_REQUEST['rows'];
	$sidx = $_REQUEST['sidx'];
	$sord = $_REQUEST['sord']; 
	//$test = $_REQUEST['keys']; 
	
	
	
	if(!$sidx) $sidx =1;
	//$table = "file_transter";
 
 	 $SQL = "SELECT COUNT(*) AS count FROM file_transter where function='论坛'";
	 $count = $db->query2_count($SQL);
	
	if( $count >0 ) {
		$total_pages = ceil($count/$limit);
	} else {
		$total_pages = 0;
	}
	if ($page > $total_pages) $page=$total_pages;
	$start = $limit*$page - $limit; // do not put $limit*($page - 1)
	if($start <0) $start = 0;
	  
	$SQL  = "SELECT * FROM  file_transter where function='论坛' order by $sidx $sord LIMIT $start,$limit";
	$result = $db->query2($SQL,"防止文件外发");
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i=0;
	foreach($result as $row){
		$responce->rows[$i]['id']=$row[id];
		if($row['block']==0)
		  $box = "<input type = checkbox checked id = '" .$row[id] . "bbs' />";
       	else
		  $box = "<input type = checkbox id = '" .$row[id] . "bbs' />";
   		 
            		 
		$responce->rows[$i]['cell']=array($row[id],$row[key],$row[address],$box);
		//$responce->rows[$i]['cell']=array($test,$test,$test,$test);
		$i++;
	}
	echo json_encode($responce);
?> 




