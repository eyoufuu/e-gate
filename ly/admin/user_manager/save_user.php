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

function inet_aton($ip){
$a = ip2long($ip);
$b = unpack("N",pack("L",$a));
return $b[1];
}

$user_name=trim($_POST['username']);
if(trim($_POST['userip']==""))
	$user_ip=0;
else
{
	$user_ip=sprintf("%u", ip2long(trim($_POST['userip'])));
//	echo $ip;
//	$user_ip=sprintf("%u", inet_aton(trim($_POST['userip'])));
}
if(trim($_POST['useraccount'])=="")
{
	$user_account="\0";
	$user_passwd="\0";
}
else
{
	$user_account=trim($_POST['useraccount']);
	$user_passwd=trim($_POST['userpassword']);
}
if($_POST['depart_select']=="")
{
	$user_dep="0";
}
else
{
	$user_dep=$_POST['depart_select'];
}
if($_POST['specip_select']=="")
{
	$user_specip="2";
}
else
{
	$user_specip=$_POST['specip_select'];
}
if($_POST['policy_select']=="")
{
	$user_policy="0";
}
else
{
	if($user_specip=="2")
		$user_policy=$_POST['policy_select'];
	else
		$user_specip=0;
}
if($_SESSION["create_user"]==1)
{
	$sql_user="insert into useraccount(`name`,`account`,`passwd`,`groupid`,`bindip`,`policyid`,`specip`) values ('".
			$user_name."', '".$user_account."', '".$user_passwd."', ".$user_dep.", ".$user_ip.", ".$user_policy.", ".$user_specip.")";
}
else
{
	$sql_user="update useraccount set `name`='".$user_name."', account='".$user_account."', passwd='".
				$user_passwd."', groupid=".$user_dep.", bindip=".$user_ip.", policyid=".$user_policy.",  specip=".$user_specip." where account_id=".$_SESSION["user_id"];
}
//echo $sql_user."<br>";
$arr = $db->query($sql_user);
?>

<?php 
header("Location: user_manager.php");
?>