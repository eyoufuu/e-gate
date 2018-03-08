<?php

  require_once('_inc.php');
  $SQL = "select proid,name,type,fileout from procat where proid<>-2 order by fileout";
  $result = $db->query2($SQL,"M",false);
  $responce = array();
  foreach($result as $row)
 {
	$responce[$row['fileout']][]= array("proid"=>$row['proid'],"name"=>$row['name'],"type"=>$row['fileout']);
 }
 echo json_encode($responce); 
?>
