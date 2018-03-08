<?php
ob_start();
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
	
	$table = "netseg";

	
	$sql_coutuser="select count(*) as count from netseg";
	$rs = $db->fetchRows($sql_coutuser);
	$count=$rs[0]['count'];

	if( $count >0 ) {
		$total_pages = ceil($count/$limit);
	} else {
		$total_pages = 0;
	}
	if ($page > $total_pages) $page=$total_pages;
	$start = $limit*$page - $limit; // do not put $limit*($page - 1)
	if($start <0) $start = 0;

	$SQL  = "select netseg.id as id, netseg.name as netseg_name, netseg.ips as ips, netseg.ipe as ipe,
		netseg.monitor as monitor, netseg.policyid as policyid, policy.name as policy_name from netseg,policy where netseg.policyid=policy.policyid and netseg.id>0 ORDER BY $sidx $sord LIMIT $start , $limit";	
	$result = mysql_query( $SQL ) or die("不能执行.".mysql_error());

	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	
	
	
	
	//$SQL  = "select id,ips,ipe,upbw,downbw from simpletractl";
	//"select `proid`,`name`,`type`,`description` from procat where `proid`=-1 order by `type` , `proid` ";
	//$result = mysql_query( $SQL ) or die("不能执行该语句.".mysql_error());


	$i=0;
	while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
		$responce->rows[$i]['id']=$row[id];
		$ip_start=long2ip($row[ips]);
		$ip_end=long2ip($row[ipe]);
		if($row[monitor]==1)
		{
			$dep_monitor="已监控";
			$policy_name=$row[policy_name];
		}
		else
		{
			$dep_monitor="未监控";
			$policy_name="未分配策略";
		}
		$responce->rows[$i]['cell']=array($row[id],$row[netseg_name],$ip_start, $ip_end, $dep_monitor, $policy_name);
		$i++;
	}        
	echo json_encode($responce);
?> 




