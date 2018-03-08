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
  
  /*
  $SQL = "select name,type from procat where proid=-1 order by type";
  $result = $db->query2($SQL,"M",false);
  $type = array();
  $str ="";
  */
  /*
  foreach ($result as $row)
  {
      $SQL1 = "select proid,name,type from procat where (type=" .$row['type']." and proid<>-1)" ;
      $result1 = $db->query2($SQL1, "M",false);
	  $ui = array();
      foreach($result1 as $row1)
	  {
	      $ui[] = array($row1['proid'],$row1['name'],$row1['type']);
	  }	  
	  $type[$row['name']]= $ui; 
  } //$type= array("helo"=>array("a"=>"orange","b"=>"banana","c"=>"apple"),"good"=>array(1,2,3,4));

  echo json_encode($type);
 */
 //以上代码暂时废弃
 
 $SQL = "select proid,name,type from procat where proid<>-1 order by type";
 $result = $db->query2($SQL,"M",false);
 $responce = array();
 foreach($result as $row)
 {
	//$responce['pro'][]= array("proid"=>$row['proid'],"name"=>$row['name'],"type"=>$row['type']);
	$responce['pro'][] =array($row['proid'],$row['name'],$row['type']);
 }
 
 
 $SQL = "select webid,name,type from webcat order by type";
 $result = $db->query2($SQL,"M",false);
 $response = array();
 foreach($result as $row)
 {
    //$responce['web'][] = array("webid"=>$row['webid'],"name"=>$row['name'],"type"=>$row['type'];
    $responce['web'][] = array($row['webid'],$row['name'],$row['type'];
 }
 
 echo json_encode($responce); 
?>
