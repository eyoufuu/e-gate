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
	$sql_del_user = "delete from useraccount where account_id=".$_GET["id"];
//	echo "删除策略：".$sql_del_policy;
	$arr = $db->query($sql_del_user);
	/*
	$sql_del_keyword = "delete from keywordinfo where policyid=".$_GET["id"];
	$arr = $db->query($sql_del_keyword);
	$sql_update_filetype = "update fileinfo set pass=0, log=0";
	$arr = $db->query($sql_update_filetype);*/
?>

<?php 
header("Location: user_manager.php");
?>