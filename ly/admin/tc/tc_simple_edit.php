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
   
   	
	$oper = $_REQUEST['oper'];
	$SQL = "";
   
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
	    $SQL = "select ips,ipe from simpletractl where id<>". $id ;
		global $db;
		$res = $db->query2( $SQL);
		foreach($res as $row)
		{
		    if(!get_iplong($row['ips'],$row['ipe'],$ipvalue))
				return false;
		}
		return true;
	}
     
	 
	 
	function test_ip_eq_from_tc($ip)
	{
	    $SQL = "select count(*) from simpletractl where ips='". $ip . "' and ipe = '" . $ip . "'" ;
		global $db;
		$res = $db->fetchOne($SQL);
		if($res == "0")
		   return true;
		else
		   return false;
	}
	
	
	
	switch($oper)
	{
	//以下必须自行修改
		case "edit":
		{
		    $ID   = $_REQUEST['id'];
			$ips  = $_REQUEST['ips'];
			$ipe  = $_REQUEST['ipe'];
			$upbw = $_REQUEST['upbw'];
			$downbw  = $_REQUEST['downbw'];
			if((!test_ipfrom_tc($ID,$ipe)) || (!test_ipfrom_tc($ID,$ips)))
			{
				echo json_encode(array('success'=>false,'message'=>"失败,ip地址冲突"));
				return;
			}			
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
			$SQL = "update simpletractl set ips='$ips',ipe='$ipe',upbw=$upbw*8, downbw=$downbw*8 where id = $ID";
		}
		break;
		
		case "del":
		{
		    $ID = $_REQUEST['id'];
			$SQL = "delete from simpletractl where id in ($ID)";
		}
		break;
		case "add":
		{
			//$ID     = $_REQUEST['ids'];
			$ips    = $_REQUEST['ips'];
			$ipe    = $_REQUEST['ipe'];
			$upbw   = $_REQUEST['upbw'];
			$downbw = $_REQUEST['downbw'];
			if($ips == $ipe)
			{
				if(!test_ip_eq_from_tc($ips))
				{
					echo json_encode(array('success'=>false,'message'=>"失败,该ip地址已经存在，可选择修改"));
					return false;
				}
			}
			else
			{
				if((!test_ipfrom_tc("-1",$ipe)) || (!test_ipfrom_tc("-1",$ips)))
				{
					echo json_encode(array('success'=>false,'message'=>"失败,ip地址冲突"));
					return false;
				}
			}	

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
			$SQL = "insert into simpletractl(ips,ipe,upbw,downbw) values('$ips','$ipe',$upbw*8,$downbw*8)";
			
		}
		break;
		
	}
	$result = $db->query2($SQL,"简单流控"); //or
	
	if($db->get_rows_count()==0)
	{
	   echo json_encode(array('success'=>false,'message'=>"失败"));
	}
	else
	{
		echo json_encode(array('succees'=>true,'message'=>"成功"));
	}
?> 




