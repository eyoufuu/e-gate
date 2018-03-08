

<?php
    require_once('_inc.php');
	
	function get_iplong($low ,$high ,$now)
   {
		$lower = ip2long($low);
		$upper = ip2long($high);
		$check = ip2long($now);
		if ($check >= $lower && $check <= $upper) 
		{ // target IP is in range. 
			return false;
		}
		return true;
	}
	
	function test_ipfrom_tc($id,$ipvalue)
	{
	    $SQL = "select value from rules where (mode=1) and (id<>$id)";
		global $db;
		$res = $db->query2( $SQL);
		foreach($res as $row)
		{   
		   $ipseg=$row['value'];
		   $ip=explode('-',$ipseg);
            if(!get_iplong($ip[0],$ip[1],$ipvalue))
			return false;
		}
		return true;
	}
	
	$table = "rules";
	$oper = $_POST['oper'];
	$SQL = "";
	switch($oper)
	{
		case "edit":
		{
		    $id = $_POST['id'];
			$ips = $_POST['ips'];
			$ipe = $_POST['ipe'];
			if((!test_ipfrom_tc($id,$ipe)) || (!test_ipfrom_tc($id,$ips)))
				{
					echo json_encode(array('success'=>false,'message'=>"失败,ip地址冲突"));
					return false;
				};
			 $ipsint = sprintf("%u", ip2long($ips));
            $ipeint = sprintf("%u", ip2long($ipe)); 
            if($ipsint > $ipeint)
              {
				 echo json_encode(array('success'=>false,'message'=>"失败,结束ip地址应大于开始ip"));
				 return false;
			  };
			  
               
             if($ipeint >= ($ipsint+253))
             {
			 	 echo json_encode(array('success'=>false,'message'=>"失败,Ip段里最多需要254台主机，请重新输入"));
				 return false;
			 };						  	
				
			$des  = $_POST['des'];
			$ip=trim($ips)."-".trim($ipe);
			$SQL = "update rules set value='$ip',description='$des'  where id=$id";
        }
		break;
		
		case "del":
		{
		    $ID = $_POST['id'];
			$SQL = "delete from  rules where id in ($ID)";
	
		}
		break;
		case "add":
		{     
			$ips = $_POST['ips'];
			$ipe = $_POST['ipe'];
			if((!test_ipfrom_tc("-1",$ipe)) || (!test_ipfrom_tc("-1",$ips)))
				{
					echo json_encode(array('success'=>false,'message'=>"失败,ip地址冲突"));
					return false;
				};
		    $ipsint = sprintf("%u", ip2long($ips));
            $ipeint = sprintf("%u", ip2long($ipe)); 
            if($ipsint > $ipeint)
              {
				 echo json_encode(array('success'=>false,'message'=>"失败,结束ip地址应大于开始ip"));
				 return false;
			  };
			  
               
             if($ipeint >= ($ipsint+253))
             {
			 	 echo json_encode(array('success'=>false,'message'=>"失败,Ip段里最多需要254台主机，请重新输入"));
				 return false;
			 };						  
			
			$cid = $_POST['id'];
			$des = $_POST['des'];
			$sql= "select name from channel where id=$cid";
			$result = $db->query2one( $sql);
			$cname =$result['name'];
			$mode="1";
			$ip=trim($ips)."-".trim($ipe);
			$SQL = "insert into rules(name,mode,value,description,channelid) values('$cname','$mode','$ip','$des',$cid)";
		}
		break;
		
	}
	
	$result = $db->query2( $SQL,"高级流控" ); //or
	if($db->get_rows_count()==0)
	{
	   echo json_encode(array('success'=>false,'message'=>"失败" . mysql_error()));
	}
	else
	{
		echo json_encode(array('succees'=>true,'message'=>"成功"));
	}
?> 




