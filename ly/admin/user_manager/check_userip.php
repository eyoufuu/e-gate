<?php
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
	$user_ip=sprintf("%u", ip2long(trim($_GET['userip'])));
	$sql_userip="select count(*) as ip_count from useraccount where bindip=".$user_ip." and account_id<>".$_SESSION["user_id"];
	$arr_userip=$db->fetchRows($sql_userip);
	if($arr_userip[0]['ip_count']==0)
		$response=0;
	else
		$response=1;
	echo $response;
		
?>