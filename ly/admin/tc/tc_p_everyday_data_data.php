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
   date_default_timezone_set('Asia/Shanghai');
   $date_ym = date('Ym');
   $date_day = date('d');
   $IP = $_REQUEST['IP'];
  // $IP = ip2long($IP);
   $tablename_pro = $date_ym . "flowdata";
   $tablename_web = $date_ym . "web";
		 //选择所有的协议出来
   $sql_temp_pro = "select  b.name as name,a.upflow as up,a.downflow as down from %s as a left join procat as b on a.pro_id=b.proid where a.ip_inner = %u and day(FROM_UNIXTIME(a.logtime))=%d limit 0, 20";
   $sql_temp_pro = sprintf($sql_temp_pro, $tablename_pro , $IP,$date_day);	 
   $result = $db->query2($sql_temp_pro);
   $pro = array();
   $web = array();
   foreach($result as $row)
		$pro[] =  array($row['name'],$row['up'],$row['down']);
		//$pro = array($row['name'],$row['up'],$row['down']);
   $sql_temp_web = "select host,url from %s where day(FROM_UNIXTIME(logtime))=%d and ip_inner = %u order by logtime desc limit 0,20"; 
   $sql_temp_web = sprintf($sql_temp_web,$tablename_web,$date_day,$IP);
   $result = $db->query2($sql_temp_web);   
   foreach ($result as $row)
   {
		$decode = urldecode ( $row['url'] );
		$code = mb_detect_encoding ( $decode, "EUC-CN,UTF-8" );
//echo $code;
		$c = mb_convert_encoding ( $decode, 'UTF-8', $code );
		$web[] =  array($row['host'],$c);
	}
   $responce=array('pro'=>$pro,'web'=>$web); 	  
   echo json_encode($responce);	  
?>

