<?php


 require_once('_inc.php');
?>


<?php

	$page = $_REQUEST['page'];
	$limit= $_REQUEST['rows'];
	$sidx = $_REQUEST['sidx'];
	$sord = $_REQUEST['sord']; 
	if(!$sidx) $sidx =1;
	$sql_coutuser="select count(*) as count from netseg";
	$count = $db->query2_count($sql_coutuser);
	if( $count >0 ) {
		$total_pages = ceil($count/$limit);
	} else {
		$total_pages = 0;
	}
	if ($page > $total_pages) $page=$total_pages;
	$start = $limit*$page - $limit; // do not put $limit*($page - 1)
	if($start <0) $start = 0;
	
	
   
	$SQL  = "select netseg.*,policy.name as policyname from netseg,policy where netseg.policyid=policy.policyid order by netseg.id LIMIT $start , $limit ";	
	$result = $db->fetchRows( $SQL );

	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;

   $i=0;
	foreach($result as $row ) {
		 $responce->rows[$i]['id']=$row[id];
		 $ip_start=long2ip($row[ips]);
		 $ip_end=long2ip($row[ipe]);
		 if($row[monitor]==1)
		  {
			$dep_monitor="是";
			$policy_name=$row[policyname];
		   }
		 else
		  {
			$dep_monitor="否";
			$policy_name="未分配策略";
		  }
	    $responce->rows[$i]['cell']=array($row[id], $ip_start,$ip_end,$row[name],$dep_monitor,$policy_name);
	    $i++;
	  
   	}        
	echo json_encode($responce);
?> 




