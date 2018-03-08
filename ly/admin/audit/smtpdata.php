<?php
   require_once('_inc.php');
?>
<?php	
date_default_timezone_set('UTC');
date_default_timezone_set('Asia/Shanghai');		
		session_start();
		if(!isset($_SESSION['date']))
			return;
		list($year,$month,$day)=explode('-',$_SESSION['date']);
		$date_ym= $year.$month;
		$tabletitle =$date_ym."smtptitle";
		$tabledata =$date_ym."smtpdata";				
		$inttime =strtotime($_SESSION['date']);
		$ips=ip2long($_SESSION['ips']);
		$ipe=ip2long($_SESSION['ipe']);
		
		if(isset($_SESSION['search']))
		{
			$gbk = iconv("","GB2312",$_SESSION['search']);//EUC-CN
			$q_p = quoted_printable_encode($gbk);
			$format = "select a.titleid,a.logtime,a.ip_inner,a.sourcemailaddr,a.destmailaddr,a.mac_address from %s as a inner join %s as b on(a.titleid=b.titleid) where (a.logtime>=%u and a.ip_inner>=%u and a.ip_inner<=%u";
			$sql = sprintf($format,$tabletitle,$tabledata,$inttime,$ips,$ipe)." and b.content like'%$q_p%')";
	//		echo $sql;
		}
		else
		{
			$format = "select titleid,logtime,ip_inner,sourcemailaddr,destmailaddr,mac_address from %s where (logtime>=%u and ip_inner>=%u and ip_inner<=%u)";
			$sql = sprintf($format,$tabletitle,$inttime,$ips,$ipe);
	///		echo $sql;
		}
		$page = $_REQUEST['page'];
		$limit= $_REQUEST['rows'];
		$sidx = $_REQUEST['sidx'];
		$sord = $_REQUEST['sord']; 

		if(!$sidx) $sidx =1;
		
	//	$format = "SELECT COUNT(*) AS count FROM %s where (logtime>=%u and ip_inner>=%u and ip_inner<=%u)";
	//	$sql=sprintf($format,$tablename,$inttime,$ips,$ipe);
		$count = $dbaudit->query2_count($sql,"M");

	//	echo "heloo:".$count;
	//	$row = $dbaudit->fetcharray($rz,MYSQL_ASSOC);

	//	$count = $row['count'];
//echo $count . "@@@@@@@@@@@@@@@@@@3";
		if( $count >0 )
		{
			$total_pages = ceil($count/$limit);
		}
		else
		{
			$total_pages = 0;
		}

		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit; // do not put $limit*($page - 1)
		if($start <0) $start = 0;
		
	//	$format="select titleid,logtime,ip_inner,sourcemailaddr,destmailaddr,mac_address from %s where (logtime>=%u and ip_inner>=%u and ip_inner<=%u) ORDER BY $sidx $sord LIMIT $start , $limit";
	//	$sql=sprintf($format,$tablename,$inttime,$ips,$ipe);
		$sql = $sql." ORDER BY $sidx $sord LIMIT $start , $limit";
	// echo "next:".$sql;
		
//	$sql = "SELECT *  FROM 201003smtptitle";
		$result = $dbaudit->query2($sql,"M",false);
		if($page==0)
		{
			if($total_pages>0)
				$page=1;
		}
		$response->page = $page;
		$response->total = $total_pages;
		$response->records = $count;
		
		$i=0;
		foreach($result as $row)
		{
		//	$response->rows[$i]['id']=$i;
			$id=$row['titleid'];
			$logtime=$row['logtime'];
			$ip_inner=$row['ip_inner'];
			$mac=$row['mac_address'];
			$src=$row['sourcemailaddr'];
			$dest=$row['destmailaddr'];
			$response->rows[$i]['cell']=array($id,date("Y-m-d H:i:s",$logtime),long2ip($ip_inner),$mac,$src,$dest,"<a href='smtpanalysis.php?ym=$date_ym&titleid=$id'>解析</a>");
			$i++;
		}
		echo json_encode($response);
?>	
		
		
		
		
