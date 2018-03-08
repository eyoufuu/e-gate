<?php


 require_once('_inc.php');
?>


<?php

	$page = $_REQUEST['page'];
	$limit= $_REQUEST['rows'];
	$sidx = $_REQUEST['sidx'];
	$sord = $_REQUEST['sord']; 
	if(!$sidx) $sidx =1;
	$sql_coutuser="select count(*) as count from filecat";
	$count = $db->query2_count($sql_coutuser);
	if( $count >0 ) {
		$total_pages = ceil($count/$limit);
	} else {
		$total_pages = 0;
	}
	if ($page > $total_pages) $page=$total_pages;
	$start = $limit*$page - $limit; // do not put $limit*($page - 1)
	if($start <0) $start = 0;
	
	
   
	$SQL  = "select * from filecat order by typeid LIMIT $start,$limit ";	
	$result = $db->fetchRows( $SQL );

	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;

   $i=0;
	foreach($result as $row ) {
		 $responce->rows[$i]['id']=$row[id];
		 $block="";
		 $log=""; 
		 $responce->rows[$i]['cell']=array($row[id], $row[name],$block,$log);
	    $i++;
	  
   	}        
	echo json_encode($responce);
?> 




