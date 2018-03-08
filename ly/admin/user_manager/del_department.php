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
	$sql_del_department = "delete from netseg where id=".$_GET["id"];
	$arr = $db->query($sql_del_department);
	/*
	$sql_del_keyword = "delete from keywordinfo where policyid=".$_GET["id"];
	$arr = $db->query($sql_del_keyword);
	$sql_update_filetype = "update fileinfo set pass=0, log=0";
	$arr = $db->query($sql_update_filetype);*/
?>

<?php 
header("Location: department_manager.php");
?>