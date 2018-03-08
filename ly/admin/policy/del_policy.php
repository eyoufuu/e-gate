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
	$sql_del_policy = "update policy set proctl=0, webfilter=0, filetypefilter=0, keywordfilter=0, smtpaudit=0, pop3audit=0, postaudit=0, time=0, week=0, times1=0, timee1=0, times2=0, timee2=0, stat=0 where policyid=".$_GET["id"];
//	echo "删除策略：".$sql_del_policy;
	$arr = $db->query($sql_del_policy);
	$sql_del_keyword = "delete from keywordinfo where policyid=".$_GET["id"];
	$arr = $db->query($sql_del_keyword);
	$sql_update_filetype = "update fileinfo set pass=0, log=0 where policyid=".$_GET["id"];
	$arr = $db->query($sql_update_filetype);
	$sql_update_netseg="update netseg set policyid=0 where policyid=".$_GET["id"];
	$arr=$db->query($sql_update_netseg);
	$sql_update_user="update useraccount set policyid=0 where policyid=".$_GET["id"];
	$arr=$db->query($sql_update_user);
?>

<?php 
header("Location: policy_name.php");
?>