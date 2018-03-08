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
			$ips = $_REQUEST['ips'];
			if($ips == "")
			 $ips="";
			$ipe = $_REQUEST['ipe'];
			 if($ipe == "")
			 $ipe="";
			$mail = $_REQUEST['mail'];
			 if($mail == "")
			 $mail="";
			$im = $_REQUEST['im']; 
			 if($im == "")
			 $im="";
			$netdisk = $_REQUEST['netdisk'];
			 if($netdisk == "")
			 $netdisk="";
			$bbs = $_REQUEST['bbs'];
			 if($bbs == "")
			 $bbs="";
			$ftp = $_REQUEST['ftp'];
			$tftp = $_REQUEST['tftp'];
		    $SQL = "update file_pass set ips='$ips',ipe='$ipe',mail='$mail', im='$im',netdisk='$netdisk',bbs='$bbs',ftp=$ftp,tftp=$tftp where id = $ID";
		}
		break;
		
		case "del":
		{
		    $ID = $_REQUEST['id'];
			$SQL = "delete from file_pass where id in ($ID)";
		}
		break;
		case "add":
		{
			//$ID     = $_REQUEST['ids'];
			$ips = $_REQUEST['ips'];
			if($ips == "")
			 $ips="";
			$ipe = $_REQUEST['ipe'];
			 if($ipe == "")
			 $ipe="";
			$mail = $_REQUEST['mail'];
			 if($mail == "")
			 $mail="";
			$im = $_REQUEST['im']; 
			 if($im == "")
			 $im="";
			$netdisk = $_REQUEST['netdisk'];
			 if($netdisk == "")
			 $netdisk="";
			$bbs = $_REQUEST['bbs'];
			 if($bbs == "")
			 $bbs="";
			$ftp = $_REQUEST['ftp'];
			$tftp = $_REQUEST['tftp'];
			//$filetype = $_REQUEST['filetype'];
			$SQL = "insert into file_pass(ips,ipe,mail,im,netdisk,bbs,ftp,tftp) values('$ips','$ipe','$mail','$im','$netdisk','$bbs',$ftp,$tftp)";
			
		}
		break;
		
	}
	$result = $db->query2($SQL); //or
	
	if($db->get_rows_count()==0)
	{
	   echo json_encode(array('success'=>false,'message'=>"失败"));
	}
	else
	{
		echo json_encode(array('succees'=>true,'message'=>"成功"));
	}
?> 




