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
   include("../dbconfig.php");
?>

<?php

  session_start();
   if(!isset($_SESSION["date1"]))
   		return;
   $ds = $_SESSION["date1"];
   $de = $_SESSION["date2"];
   $symbol= $_SESSION["symbol"]; 
   
   list($year,$month,$day)=explode('-',$ds);
   $date_ym = $year.$month;
   $tablename=$date_ym."web";
	
   $dates = strtotime($ds);
   $datee = strtotime($de);
 
   

	$db_1 = mysql_connect($dbhost, $dbuser, $dbpassword) or die("连接错误: " . mysql_error());
	mysql_select_db($database) or die("Error conecting to db."); 
	
		
	$page = $_REQUEST['page'];
	$limit= $_REQUEST['rows'];
	$sidx = $_REQUEST['sidx'];
	$sord = $_REQUEST['sord']; 
	if(!$sidx) $sidx =1;
		
	 switch($symbol)
    {
    	case "1":
    		$result ="SELECT COUNT(*) AS count FROM " . $tablename." where (get_type=3) and (logtime between ".$dates." and ".$datee.")";
   	   $SQL = " call keywordip_all('$tablename',$dates,$datee,$start,$limit)";
    		break;
    	case "2":
    	    
    		$username =  $_SESSION["username"];
    		 
    		 $arr = "select id from useraccount where name= '$username'";
		    $arr = $db->fetchRow($arr);
		    $accountid= $arr["id"];
		    
  		    $result ="SELECT COUNT(*) AS count FROM " . $tablename." where (accountid=$account) and (get_type=3) and (logtime between ".$dates." and ".$datee.")";
          $SQL = " call keywordip_all('$tablename',$dates,$datee,accountid,$start,$limit)";   
   	break;
    	case "3":
    		$account = $_SESSION["account"];
    		$arr = "select id from useraccount where account= '$account'";
		    $arr = $db->fetchRow($arr);
		    $accountid= $arr["id"];
		   $result ="SELECT COUNT(*) AS count FROM " . $tablename." where (accountid=$account) and (get_type=3) and (logtime between ".$dates." and ".$datee.")";
         $SQL = " call keywordip_all('$tablename',$dates,$datee,accountid,$start,$limit)";      		
   		break;
    	case "4":
			$ipsi = ip2long($_SESSION["ips"]);
			$ipei = ip2long($_SESSION["ipe"]);
			
			$result ="SELECT COUNT(*) AS count FROM " . $tablename." where (ip_inner between $ipsi and $ipei) and (get_type=3) and (logtime between ".$dates." and ".$datee.")";
   	
			$SQL = " call keywordip_all('$tablename',$dates,$datee,$ipsi,$ipei,$start,$limit)";   
    		break;
    	default:
    		return;
    }
	
	$row = $db->fetchRow($result);
	$count = $row['count'];
   


 
  if( $count >0 ) {
		$total_pages = ceil($count/$limit);
	} else {
		$total_pages = 0;
	}
	if ($page > $total_pages) $page=$total_pages;
	$start = $limit*$page - $limit; // do not put $limit*($page - 1)
	if($start <0) $start = 0;

	 switch($symbol)
    {
    	case "1":
    		$SQL = " call keywordip_all('$tablename',$dates,$datee,$start,$limit)";
    		break;
    	case "2":
    	    
    		$SQL = " call keywordip_all('$tablename',$dates,$datee,accountid,$start,$limit)";   
   	break;
    	case "3":
    		$SQL = " call keywordip_all('$tablename',$dates,$datee,accountid,$start,$limit)";      		
   		break;
    	case "4":
			
			$SQL = " call keywordip_all('$tablename',$dates,$datee,$ipsi,$ipei,$start,$limit)";   
    		break;
    	default:
    		return;
    }
	$result = mysql_query( $SQL ) or die("不能执行.".mysql_error()); 

	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i = 0;
	while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
			$responce->row[$i]['id']=$row[id];
			 if  ($row[pass] == 1)
			  $pass='放行';
			  else
			  $pass='阻断';
			  
			$responce->rows[$i]['cell']=array($row[id],$row[logtime],$row[name],$row[ip_inner],$row[host],$row[keyword], $pass);
			$i++;
		
		}
	
  echo json_encode($responce);
	 mysql_close($db_1); 
?> 




