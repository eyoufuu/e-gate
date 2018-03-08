<?php 
	require_once('_inc.php');
	$param1= $_REQUEST['ym'];
	$param2= $_REQUEST['titleid'];
/*	$tabletitle = $_POST['tablename']."smtptitle";
	$sqltitle="select logtime,ip_inner,sourcemailaddr,destmailaddr,mac_address from $tabletitle where titleid=$param2";
	$arr = $dbaudit->fetchRows($sqltitle);
	foreach($arr as $value)
	{
		date_default_timezone_set('Asia/Shanghai');
//		echo date("Y-m-d H:i:s",$value['logtime']);
//		echo "<br />";
//		echo "ip地址：".long2ip($value['ip_inner'])."mac地址：".$value['mac_address'];
//		echo "<br />";
//		echo "发件地址：".$value['sourcemailaddr']."收件地址：".$value['destmailaddr'];
		
	}*/
//	echo "<br />";
	$tablecontent= $_REQUEST['ym']."smtpdata";
//	echo $tablecontent;
//	echo $param2;
	$sqlcontent="select content from $tablecontent where titleid=$param2 order by seqnum";
	echo $sqlcontent;
	$arr1 = $dbaudit->query2($sqlcontent,"M",false);
//	echo "内容：";
//	echo "<br />";
	$filename="/home/tmp.eml";
	if(!$fh=fopen($filename,"w+"))
	{
		//echo "open faild";	
	}
	foreach($arr1 as $value)
	{
		fwrite($fh, $value['content']);
	}
	fclose($fh);
	ob_start(); 	
  $dest="tmp.eml";   
  down_file($filename,$dest);   
  function   down_file($content,$dest)
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
