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
?>
<?php
	$arr_ipstart=split(".", $_REQUEST['ip_start']);
	$arr_ipend=split(".", $_REQUEST['ip_end']);
	if(intval($arr_ipstart[0])==intval($arr_ipend[0]))
		$ip_first=$arr_ipstart[0];
	else
		$ip_first=$arr_ipstart[0]."-".$arr_ipend[0];
	if(intval($arr_ipstart[1])==intval($arr_ipend[1]))
		$ip_second=$arr_ipstart[1];
	else
		$ip_second=$arr_ipstart[1]."-".$arr_ipend[1];
	if(intval($arr_ipstart[2])==intval($arr_ipend[2]))
		$ip_third=$arr_ipstart[2];
	else
		$ip_third=$arr_ipstart[2]."-".$arr_ipend[2];
	$nmap_ip="/usr/local/apache/htdocs/ly/admin/user_manager/ip_scan ".$ip_first.".".$ip_second.".".$ip_third.".1-254";
//	$nmap_ip="/usr/local/apache/htdocs/ly/admin/user_manager/ip_scan 192.168.0.1/24";
//	echo $nmap_ip."<br>";
	exec($nmap_ip,$a,$b);
	header("Location: user_manager.php");
?>