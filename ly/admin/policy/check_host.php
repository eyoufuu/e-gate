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
	$web_host=trim($_GET['host']);
	$sql_webhost="select count(*) as host_count from specweb where host='".$web_host."' and id<>".$_SESSION["blackweb_id"];
	$arr_webhost=$db->fetchRows($sql_webhost);
	if($arr_webhost[0]['host_count']==0)
		$response=0;
	else
		$response=1;
	echo $response;
		
?>