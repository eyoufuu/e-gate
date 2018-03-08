<?php
require_once('_inc.php');
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
   if($dates==$datee)
     $datee=$datee+86400; 
   
   $arr = "select systemmode from globalpara";
   $arr = $db->fetchRow($arr);
   $systemmode= $arr["systemmode"];
   if ($systemmode == 0)
    $mode='用户';
   else
    $mode='帐号';  
   
    $page = $_REQUEST['page'];
	$limit= $_REQUEST['rows'];
	$sidx = $_REQUEST['sidx'];
	$sord = $_REQUEST['sord']; 
    
	if(!$sidx) $sidx =1;
	switch($symbol)
    {
    	case "1":
    		$result ="SELECT COUNT(*) AS count FROM " . $tablename." where (get_type=1) and (logtime between ".$dates." and ".$datee.")";
   	  	break;
    	case "2":
    	    
    		 $username =  $_SESSION["username"];
    		  if ($systemmode == 0)
            {
              $arr = "select bindip from useraccount where name= '$username'";
		        $arr = $db->fetchRow($arr);
		        $accountid= $arr["bindip"];
		    
  		        $result ="SELECT COUNT(*) AS count FROM " . $tablename." where (ip_inner=$accountid) and (get_type=1) and (logtime between ".$dates." and ".$datee.")";
         
            }
           else
            {
              $arr = "select id from useraccount where name= '$username'";
		        $arr = $db->fetchRow($arr);
		        $accountid= $arr["id"];
		    
  		        $result ="SELECT COUNT(*) AS count FROM " . $tablename." where (account_id=$accountid) and (get_type=1) and (logtime between ".$dates." and ".$datee.")";
          
            }       
   
    			break;
    	case "3":
    		 $account = $_SESSION["account"];
    		 $arr = "select id from useraccount where account= '$account'";
		    $arr = $db->fetchRow($arr);
		    $accountid= $arr["id"];
		    $result ="SELECT COUNT(*) AS count FROM " . $tablename." where (account_id=$accountid) and (get_type=1) and (logtime between ".$dates." and ".$datee.")";
           break;
    	case "4":
			$ipsi = bindec(decbin(ip2long($_SESSION["ips"])));
			$ipei = bindec(decbin(ip2long($_SESSION["ipe"])));
			
	      $result ="SELECT COUNT(*) AS count FROM " . $tablename." where (ip_inner between $ipsi and $ipei) and (get_type=1) and (logtime between ".$dates." and ".$datee.")";
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
    	    if ($systemmode == 0)
              $SQL="select  u.id ,u.logtime,w.name,u.ip_inner,v.name,u.host,u.pass from (select id, FROM_UNIXTIME(logtime) as logtime,ip_inner,host,typeid ,pass from $tablename  where (get_type=1) and (logtime between $start and $datee) limit $start,$limit) as u left join (select webid,name from webcat ) as v on u.typeid=v.webid left join (select name,bindip from useraccount) as w on u.ip_inner=w.bindip ";
		   else
             $SQL = " select u.id ,u.logtime,w.account as name,u.ip_inner,v.name as webcat,u.host,u.pass from (select id, FROM_UNIXTIME(logtime) as logtime,ip_inner,host,typeid ,pass from  $tablename where (get_type=1) and (logtime between $start and $datee) limit $start,$limit) as u left join (select webid,name from webcat ) as v on u.typeid=v.webid left join (select id,account from useraccount) as w on u.account_id=w.id ";
    	   break;
    	case "2":
    	    
    		if ($systemmode == 0)
             $SQL = " select u.id,u.logtime,w.name,u.ip_inner,v.name as webcat,u.host,u.pass from (select id, FROM_UNIXTIME(logtime) as logtime,ip_inner,account_id,host,typeid ,pass from $tablename where (ip_inner=$accountid) and (get_type=1) and (logtime between  $start and $datee )  limit $start,$limit) as u  left join (select webid,name from webcat) as v on  v.webid=u.typeid  left join (select name,bindip from useraccount) as w on u.ip_inner=w.bindip ";
    		
          else
             $SQL = "select u.id,u.logtime,w.account as name,u.ip_inner,v.name as webcat,u.host,u.pass from (select id, FROM_UNIXTIME(logtime) as logtime,ip_inner,account_id,host,typeid ,pass from $tablename where (account_id=$accountid) and (get_type=1) and (logtime between $start and $datee)  limit $start,$limit) as u  left join (select webid,name from webcat) as v on  v.webid=u.typeid  left join (select id,account from useraccount) as w on u.account_id=w.id";
    		  
   	   break;
    	case "3":
    		$SQL = "select u.id,u.logtime,w.account as name,u.ip_inner,v.name as webcat,u.host,u.pass from (select id, FROM_UNIXTIME(logtime) as logtime,ip_inner,account_id,host,typeid ,pass from $tablename where (account_id=$accountid) and (get_type=1) and (logtime between $start and $datee)  limit $start,$limit) as u  left join (select webid,name from webcat) as v on  v.webid=u.typeid  left join (select id,account from useraccount) as w on u.account_id=w.id";   		
   		break;
    	case "4":
			 if ($systemmode == 0)
              $format = "select u.id,u.logtime,w.name,u.ip_inner,v.name as webcat,u.host,u.pass from (select id, FROM_UNIXTIME(logtime) as logtime,ip_inner,account_id,host,typeid ,pass from  %s where (ip_inner between  %u and %u) and (get_type=1) and (logtime between %u and %u)  limit %u,%u) as u  left join (select webid,name from webcat) as v on  v.webid=u.typeid  left join (select name,bindip from useraccount) as w on u.ip_inner=w.bindip ";   
    	  	  $SQL=sprintf($format,$tablename,$ipsi,$ipei,$dates,$datee,$start,$limit);		
          else
             $format = "select u.id,u.logtime,w.account as name,u.ip_inner,v.name as webcat,u.host,u.pass from (select id, FROM_UNIXTIME(logtime) as logtime,ip_inner,account_id,host,typeid ,pass from %s where (ip_inner between %u and %u) and (get_type=1) and (logtime between %u and %u)  limit %u,%u) as u  left join (select webid,name from webcat) as v on  v.webid=u.typeid  left join (select id,account from useraccount) as w on u.account_id=w.id ";   
             $SQL=sprintf($format,$tablename,$ipsi,$ipei,$dates,$datee,$start,$limit);	
		   break;
    	  default:
    	  default:
    		return;
    }
   $result = $db->fetchRows( $SQL );


	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i = 0;
	 foreach($result as $row ) {
			$responce->row[$i]['id']=$row[id];
			 if  ($row[pass] == 1)
			  $pass='放行';
			  else
			  $pass='阻断';
			  
			$responce->rows[$i]['cell']=array($row[id],$row[logtime],$row[name],$row[ip_inner],$row[webcat],$row[host], $pass);
			$i++;
		   
		}
	
    echo json_encode($responce);
	 mysql_close($db_1); 
	 
?> 




