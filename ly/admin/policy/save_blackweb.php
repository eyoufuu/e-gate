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
$web_name=trim($_POST['webname']);
$web_description=trim($_POST['webdescription']);
if($_SESSION["create_blackweb"]==1)
{
	$sql_web="insert into specweb(`host`,`pass`,`description`) values ('".
			$web_name."', ".$_POST['web_select'].", '".$web_description."')";
}
else
{
	$sql_web="update specweb set `host`='".$web_name."', pass=".$_POST['web_select'].", description='".
				$web_description."' where id=".$_SESSION["blackweb_id"];
}
$arr = $db->query($sql_web);
$db->close();
?>

<script> 
var a=1;
window.returnValue = a 
window.close() 
</script> 

<?php 
//header("Location: black_web.php");
?>