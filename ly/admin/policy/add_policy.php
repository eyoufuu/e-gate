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
$sql = "select policyid from policy where stat=0 and policyid>0 limit 1";
$arr = $db->fetchRows($sql);
foreach($arr as $value){
	header("Location: policy_set.php?id=".$value['policyid']."&create=1");
}
?>