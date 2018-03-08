<?php
   require_once('_inc.php');
?>
<?php
function getcolor()
{
	static $colorvalue;
	if($colorvalue=="'bgFleet'")
		$colorvalue="";
	else
		$colorvalue="bgFleet";
	return($colorvalue);
}
?>
<html>
	<head>		
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
			<link href="../common/main.css" rel="stylesheet" type="text/css"/>
			<script type="text/javascript" src="../common/common.js"></script>
	</head>
	<body>
		<div class="bodyTitle">
			<div class="bodyTitleLeft"></div>
		  	<div class="bodyTitleText">接收邮件列表</div>
		</div>
		<form name=queryinput action="query_pop3.php" method="post" onsubmit="return checkinput()" >
			<div>	 
			地址范围：<input id="ips" name="ips" type="text"  value="" disabled="true" />-<input id="ipe" name="ipe" type="text" value="" disabled="true" />
			         <input name="haveip" id="haveip" type="checkbox" value="2" onclick="onmark(this)" />
			</div>
			<div> 
			日期：<input name="date1" class="Wdate" type="text"  onfocus="new WdatePicker(this,null,false,'whyGreen')" value=<?php date_default_timezone_set('Asia/Shanghai');echo date('Y-m-d');?> />
				    <input type="submit" value="解析"  />帮助：返回当前日期以后一个月的数据
			</div>		   		  
		</form>
		<table width="100%" border="0" cellspacing="0" cellpadding="2">
			<tr>
				<td width="40" class="bgFleet borderBottom">时间</td>
				<td width="50" class="bgFleet borderBottom">ip</td>
				<td align="center" class="bgFleet borderBottom">解析</td>
			</tr>		
			<?php
			date_default_timezone_set('Asia/Shanghai');
			list($year,$month,$day)=split('[-]',$_POST['date1']);
			$date_ym= $year.$month;
			$tablename=$date_ym."pop3data";						
			$inttime =strtotime($_POST['date1']);
			$ips=ip2long($_POST['ips']);
			$ipe=ip2long($_POST['ipe']);	
			printf("%u\n %u\n", $ips,$ipe);
			echo $inttime;
			echo $tablename;						
			echo $_POST['date1'];
			echo $date_ym;
			if($ips=="" ||$ipe=="")
			{
				$sql="select logtime,ip_inner,port_inner,ip_outter,port_outter,ack from $tablename where logtime>=$inttime group by logtime,ip_inner,port_inner,ip_outter,port_outter";
			}
			else
			{
				$sql="select logtime,ip_inner,port_inner,ip_outter,port_outter,ack from $tablename where (logtime>=$inttime and ip_inner>=$ips and ip_inner<=$ipe) group by logtime,ip_inner,port_inner,ip_outter,port_outter";
			}
			$arr = $dbaudit->fetchRows($sql);
			foreach($arr as $value){
			$color = getcolor();
			?> 
			<tr>
			    <td align="center" class= <?php echo $color?>><?php echo date("Y-m-d H:i:s",$value['logtime']);?></td>
			    <td align="center" class= <?php echo $color?>><?php echo long2ip($value['ip_inner']);?></td>
			    <td><form name=querycontent action="analysis_pop3.php" method="post">
			    	<input name="tablename" type="hidden" value="<?php echo $tablename;?>" />
			    	<input name="logtime" type="hidden" value="<?php echo $value['logtime'];?>" />
			    	<input name="ipinner" type="hidden" value="<?php echo $value['ip_inner'];?>" />
			    	<input name="portinner" type="hidden" value="<?php echo $value['port_inner'];?>" />
			    	<input name="ipoutter" type="hidden" value="<?php echo $value['ip_outter'];?>" />
			    	<input name="portoutter" type="hidden" value="<?php echo $value['port_outter'];?>" />
			    	<input name="ack" type="hidden" value="<?php echo $value['ack'];?>" />
			    	<input type="submit" value="解析" />
			    	</form></td>		    
			</tr> 
			<?php
			}
			unset($arr);
			?>
		</table>
	</body>
</html>