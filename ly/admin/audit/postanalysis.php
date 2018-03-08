<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
<?php
require_once ('_inc.php');
//header ( "Content-Type: text/html; charset=utf-8" );
date_default_timezone_set('Asia/Shanghai');
$logtime = $_REQUEST['logtime'];
$ipinner = $_REQUEST['ipinner'];
$portinner = $_REQUEST['portinner'];
$ipoutter = $_REQUEST['ipoutter'];
$portoutter = $_REQUEST['portoutter'];
$tablename = $_REQUEST['tablename'];
$mac=$_REQUEST['mac'];
$sqlcontent = "select content from $tablename where (logtime=$logtime and ip_inner=$ipinner and port_inner=$portinner and ip_outter=$ipoutter and port_outter=$portoutter) order by seqnum";
$arr = $dbaudit->fetchRows ( $sqlcontent );

foreach ( $arr as $value ) {
	//	$c = mb_convert_encoding($value['content'],'UTF-8','GB2312');
	//	echo urldecode($c);
	$content = $content . $value ['content'];
}
$pcont = strstr ( $content, "\r\n\r\n" );
//$c=mb_convert_encoding($content,'GB2312');
$decode = urldecode ( $pcont );
/*	echo "解码方式一：";
	echo "<br>";
	$c=iconv('UTF-8','UTF-8//IGNORE',$decode);
	echo $decode;
	echo "<br>";
	echo "-------------------------------------------------------------------------------------";
	echo "<br>";	
	echo "解码方式二：";
	echo "<br>";
	$d=iconv('GB2312','UTF-8//IGNORE',$decode);
	echo $d;*/
$sql1="select logtime,ip_inner,mac_address,host,url from $tablename where (logtime=$logtime and ip_inner=$ipinner and port_inner=$portinner and ip_outter=$ipoutter and port_outter=$portoutter) group by logtime,ip_inner,port_inner,ip_outter,port_outter";
$result = $dbaudit->fetchRow($sql1);

echo "IP地址：".long2ip($result['ip_inner'])."<br/>";
echo "MAC地址：".$result['mac_address']."<br>";
echo "发帖时间：".date("Y-m-d H:i:s",$result['logtime'])."<br/>";
echo "发帖地址：http://".$result['host'].$result['url']."<br/>";
echo "发帖内容：";
//echo mb_detect_encoding ( $content, "EUC-CN,UTF-8" );
//echo "@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@";
$code = mb_detect_encoding ( $decode, "EUC-CN,UTF-8" );
//echo $code;
$c = mb_convert_encoding ( $decode, 'UTF-8', $code );
echo "<p>&nbsp;</p>".$c;
?>
</body>
</html>