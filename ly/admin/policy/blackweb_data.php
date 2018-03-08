<?php
//ob_start();
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
	if(!$sidx) $sidx =1;
	
	
	$sql_coutuser="select count(*) as count from specweb";
	$count=$db->fetchOne($sql_coutuser);

	if( $count >0 ) {
		$total_pages = ceil($count/$limit);
	} else {
		$total_pages = 0;
	}
	if ($page > $total_pages) $page=$total_pages;
	$start = $limit*$page - $limit; // do not put $limit*($page - 1)
	if($start <0) $start = 0;

	$SQL  = "select * from specweb ORDER BY $sidx $sord LIMIT $start , $limit";	
	$result = $db->query2($SQL,"黑白网址名单");

	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;

	$i=0;
	foreach($result as $row){
		$responce->rows[$i]['id']=$row['id'];
		if($row[pass]==0)
			$pass='阻挡';
		else
			$pass='放行';
		$responce->rows[$i]['cell']=array($row['id'],$row['host'], $pass,$row['description']);
		$i++;
	}        
	echo json_encode($responce);
?> 




