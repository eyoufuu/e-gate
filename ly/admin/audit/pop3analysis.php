<?php 
	require_once('_inc.php');
	$tablename = $_REQUEST['tablename'];
	$logtime= $_REQUEST['logtime'];
	$ipinner= $_REQUEST['ipinner'];
	$portinner= $_REQUEST['portinner'];
	$ipoutter= $_REQUEST['ipoutter'];
	$portoutter= $_REQUEST['portoutter'];
	$ack = $_REQUEST['ack'];
	
	$sql="select content from $tablename where (logtime=$logtime and ip_inner=$ipinner and port_inner=$portinner and ip_outter=$ipoutter and port_outter=$portoutter and ack=$ack) order by seqnum";
	$arr = $dbaudit->query2($sql,"M",false);
	
	$filename="/home/tmp.eml";
	if(!$fh=fopen($filename,"w+"))
	{
		//echo "open faild";	
	}
	foreach($arr as $value)
	{
		fwrite($fh, $value['content']);
	}
	fclose($fh);
	ob_start(); 	
  $dest="tmp.eml";   
  down_file($filename,$dest);   
  function down_file($content,$dest)
  {   
	  header("Pragma:public");   
	  header("Expires:0");   
	//  header("Cache-Control:must-revalidate,post-check=0,pre-check=0");   
	  header("Content-Type:application/txt");   
	  header("Content-Disposition:attachment;filename=$dest;");   
	  header("Content-Transfer-Encoding:Binary");
	  header("Content-Length:".(string)(filesize($filename)));
	  readfile($content);   
	  //echo   $content;   
  } 
  ob_end_flush(); 
?>