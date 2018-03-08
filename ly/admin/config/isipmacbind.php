<?php
   require_once('_inc.php');
   include("../dbconfig.php");
	   $sql = "update globalpara set isipmacbind = ".$_REQUEST['ipmac'];
	   $db->query2($sql,"全局变量",true);

?>




