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
$login_mode=$_POST['loginmode_select'];
$sql_loginmode="update globalpara set systemmode=".$login_mode;
$arr = $db->query2($sql_loginmode);

?>

<?php 
header("Location: user_manager2.php");
?>