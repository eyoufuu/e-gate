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
$dep_name=trim($_POST['departmentname']);
$ip_start=sprintf("%u", ip2long(trim($_POST['ipstart'])));
$ip_end=sprintf("%u", ip2long(trim($_POST['ipend'])));
if($_POST['monitor']==1)
{
	$monitor=1;
	$policyid=$_POST['policy_select'];
}
else
{
	$monitor=0;
	$policyid=0;
}
if($_SESSION["create_department"]==1)
{
	$sql_dep="insert into netseg(`name`,`ips`,`ipe`,`monitor`,`policyid`) values ('".
			$dep_name."', ".$ip_start.", ".$ip_end.", ".$monitor.", ".$policyid.")";
}
else
{
	$sql_dep="update netseg set `name`='".$dep_name."', ips=".$ip_start.", ipe=".
				$ip_end.", monitor=".$monitor.", policyid=".$policyid." where id=".$_SESSION["department_id"];
}
$arr = $db->query($sql_dep);
?>

<?php 
header("Location: department_manager.php");
?>