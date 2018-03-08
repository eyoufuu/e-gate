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
		$tablename=$date_ym."pop3data";						
		$inttime =strtotime($_SESSION['date']);
		$ips=ip2long($_SESSION['ips']);
		$ipe=ip2long($_SESSION['ipe']);
		
		if(isset($_SESSION["search"]))
		{
			$gbk = iconv("","GB2312",$_SESSION["search"]);//EUC-CN
			$q_p = quoted_printable_encode($gbk);
			$format = "logtime>=%u and ip_inner>=%u and ip_inner<=%u ";
			$sqlwhere = sprintf($format,$inttime,$ips,$ipe)."and (content like'%$q_p%')";
		}
		else
		{
			$format = "logtime>=%u and ip_inner>=%u and ip_inner<=%u";
			$sqlwhere = sprintf($format,$inttime,$ips,$ipe);
		}
//		printf("%u\n %u\n", $ips,$ipe);
//		echo $inttime;
//		echo $tablename;						
//		echo $_POST['date1'];
//		echo $date_ym;
		$page = $_REQUEST['page'];
		$limit= $_REQUEST['rows'];
		$sidx = $_REQUEST['sidx'];
		$sord = $_REQUEST['sord']; 

		if(!$sidx) $sidx =1;
		
		$sql = "select COUNT(*) as abc from (select count(logtime) from $tablename where ($sqlwhere) group by logtime,ip_inner,port_inner,ip_outter,port_outter)xxx ";
	//	$sql=sprintf($format,$tablename,$inttime,$ips,$ipe);
		$count=$dbaudit->query2_count($sql,"M");
//		echo $sql;
//echo "<br/>".$count."tttt".$row['abc']."#########################################";
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
		
		
		$sql="select logtime,ip_inner,port_inner,ip_outter,port_outter,ack from $tablename where ($sqlwhere) group by logtime,ip_inner,port_inner,ip_outter,port_outter ORDER BY $sidx $sord LIMIT $start , $limit";
	//	$sql=sprintf($format,$tablename,$inttime,$ips,$ipe);
	//	$sql = "select logtime,ip_inner,port_inner,ip_outter,port_outter,ack from 201003pop3data ";
	//	echo $sql;
	
		
	//	$arr = $dbaudit->fetchRows($sql);
		
		$result = $dbaudit->query2($sql,"M",false);
		if($page==0)
		{
			if($total_pages>0)
				$page=1;
		}
		$responce->page = $page;
		$responce->total = $total_pages;
		$responce->records = $count;
		$i=0;
		foreach($result as $row) {
		//	$response->rows[$i]['id']=$i;
			$logtime=$row['logtime'];
			$ip_inner=$row['ip_inner'];
			$port_inner=$row['port_inner'];
			$ip_outter=$row['ip_outter'];
			$port_outter=$row['port_outter'];
			$ack=$row['ack'];
			$responce->rows[$i]['cell']=array(date("Y-m-d H:i:s",$logtime),long2ip($ip_inner),"<a href='pop3analysis.php?tablename=$tablename&logtime=$logtime&ipinner=$ip_inner&portinner=$port_inner&ipoutter=$ip_outter&portoutter=$port_outter&ack=$ack'>解析</a>");
			$i++;
		//	echo  "tttttttttttttttttttttttttttttttt$i";		
		}
		echo json_encode($responce);
?>