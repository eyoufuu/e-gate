<?php
   require_once('_inc.php');
   $SQL = "select proid,name from procat where proid<>-1 ";
   $result = $db->query2($SQL,"M",false);		
   foreach($result as $row)
   {
	   $res_pro[$row['proid']] = $row['name'];
   }
   echo json_encode($res_pro);
   
?>