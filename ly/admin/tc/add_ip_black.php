<?php
require_once('_inc.php');
	$ip = ip2long($_REQUEST['ip']);
	$format = "select count(*) from specip where ip=%u";
	$sql = sprintf($format,$ip);
	$count = $db->query2_count($sql,"M");
	if($count>0)
	{
		$format = "update specip set pass=0 where ip=%u";
		$sql = sprintf($format,$ip);
		$db->query2($sql,"添加黑名单",true);
	}
	else {
		$format = "insert into specip (ip,pass,description) values(%u,0,'流量过高')";
		$sql = sprintf($format,ip2long($_REQUEST['ip']));
		$db->query2($sql,"添加黑名单",true);
	}
	system($gCmd." 6");
?>