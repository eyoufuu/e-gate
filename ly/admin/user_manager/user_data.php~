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
/*function long2ip_fix($ip_32)
{
	$ip = long2ip($ip_32);
	//先判断是big-endian还是little-endian
	$foo = 0x3456789a;
	switch (pack ('L', $foo)) 
	{
		case pack ('V', $foo):
			//little-endian
			$tmp = split("\.",$ip);
			$ip = $tmp[3].".".$tmp[2].".".$tmp[1].".".$tmp[0];
			break;
		case pack ('V', $foo):
			//big-endian
			//Nothing
			break;
		default:
	}
	return $ip;
}*/
?>

<?php

	$page = $_REQUEST['page'];
	$limit= $_REQUEST['rows'];
	$sidx = $_REQUEST['sidx'];
	$sord = $_REQUEST['sord']; 
	if(!$sidx) $sidx =1;
	
	$table = "useraccount";

	
	$sql_coutuser="select count(*) as count from useraccount";
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

	$SQL  = "select useraccount.*, netseg.name as dep_name, policy.name as policy_name from useraccount, 
		netseg, policy where useraccount.groupid=netseg.id and useraccount.policyid=policy.policyid order by useraccount.account_id LIMIT $start , $limit";	
	$result = mysql_query( $SQL ) or die("不能执行.".mysql_error());

	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	
	
	
	
	//$SQL  = "select id,ips,ipe,upbw,downbw from simpletractl";
	//"select `proid`,`name`,`type`,`description` from procat where `proid`=-1 order by `type` , `proid` ";
	//$result = mysql_query( $SQL ) or die("不能执行该语句.".mysql_error());


	$i=0;
	while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
		$responce->rows[$i]['id']=$row[account_id];
		if($row[bindip]=="0")
			$ip_bind="";
		else
		{
			$ip_bind=long2ip($row[bindip]);
		}
		if($row[specip]=="0")
		{
			$user_policy="黑名单策略";
		}
		else if($row[specip]=="1")
		{
			$user_policy="白名单策略";
		}
		else
		{
			$user_policy=$row[policy_name];
		}
		if($row[groupid]=="0")
		{
			$netseg_name = "未分配网段"; 
		}
		else 
		{
			$netseg_name = $row[dep_name];
		}
		if($row[account]=="\0")
			$user_account="";
		else
			$user_account=$row[account];
		$responce->rows[$i]['cell']=array($row[account_id],$row[name],$user_account, $ip_bind, $netseg_name, $user_policy);
		$i++;
	}        
	echo json_encode($responce);
?> 




