<?php
   require_once('_inc.php');
   include("../dbconfig.php");
   
   $sql = "update globalpara set isipmacbind = ".$_POST['ipmac'];
   $count=$dbaudit->query2_count($sql,"M");
?>




