<!DOCTYPE html>
<?php
   require_once('_inc.php');
?>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>updownflow</title>	
		<link href="../common/main.css" rel="stylesheet" type="text/css"/>
	 	<link type="text/css" rel="stylesheet" href="./mychart/demopage.css"/>		
		<script type="text/javascript" src="./mychart/enhance.js"></script>		
		<script type="text/javascript">
		// Run capabilities test
		enhance({
			loadScripts: [
				'./mychart/excanvas.js',
				'./mychart/jquery.min.js',
				'./mychart/visualize.jQuery.js',
				'./mychart/pro.js'
			],
			loadStyles: [
				'./mychart/visualize.css',
				'./mychart/visualize-light.css'
			]	
		});   
    	</script>		
     </head>
<body>	
<?php 
   session_start();
   if(!isset($_SESSION["date1"]))
   		return;
   $ds = $_SESSION["date1"];
   $de = $_SESSION["date2"];
   $symbol= $_SESSION["symbol"]; 
   
   list($year,$month,$day)=explode('-',$ds);
   $date_ym = $year.$month;
   $tablename=$date_ym."flowdata";
	
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
   
  
    switch($symbol)
    {
    	case "1":
           $show="全部";
    	   if ($systemmode == 0)
    		 $arr = "select v.name,INET_NTOA(u.ip) as ip,u.blocknum from (select ip_inner as ip,sum(packets_blocked_num) as blocknum from $tablename where logtime between  $dates and $datee  group by ip_inner order by blocknum desc limit 0 ,9) as u left join (select name, bindip from useraccount) as v on u.ip=v.bindip";
    	   else
             $arr = "select v.account as name,INET_NTOA(u.ip) as ip,u.blocknum from (select account_id, ip_inner as ip,sum(packets_blocked_num) as blocknum from $tablename where logtime between  $dates and $datee  group by ip_inner order by blocknum desc limit 0 ,9) as u left join (select id ,account from useraccount) as v on u.account_id=v.id";
        	break;
    	case "2":
    	    $username =  $_SESSION["username"];
    		$show=$username; 
         if ($systemmode == 0)
            {    		
               $arr = "select bindip from useraccount where name= '$username'";
               $arr = $db->fetchRow($arr);
		       $bindip = $arr["bindip"];
               $arr = "select v.name,INET_NTOA(u.ip) as ip,u.blocknum from (select ip_inner as ip,sum(packets_blocked_num) as blocknum from $tablename where (ip_inner=$bindip) and (logtime between  $dates and $datee )) as u left join (select name,bindip from useraccount) as v on u.ip_inner=v.bindip";
    		} else
            {
    		      $arr = "select id from useraccount where name= '$username'";
               $arr = $db->fetchRow($arr);
		         $accountid= $arr["id"];
		         $arr = "select v.account as name,INET_NTOA(u.ip) as ip,u.blocknum from (select account_id ,ip_inner as ip,sum(packets_blocked_num) as blocknum from $tablename where (account_id=$accountid) and (logtime between $dates and $datee)) as u left join (select account,id from useraccount) as v on u.account_id=v.id";
		          
           	}
		   break;
    	case "3":
    		$account = $_SESSION["account"];
            $show = $account;
    		$arr = "select id from useraccount where account= '$account'";
		    $arr = $db->fetchRow($arr);
		    $accountid= $arr["id"];
		    $arr = "select v.account as name,INET_NTOA(u.ip) as ip,u.blocknum from (select account_id ,ip_inner as ip,sum(packets_blocked_num) as blocknum from $tablename where (account_id=$accountid) and (logtime between $dates and $datee)) as u left join (select account,id from useraccount) as v on u.account_id=v.id";
			break;
    	case "4":
			$ipsi = ip2long($_SESSION["ips"]);
			$ipei = ip2long($_SESSION["ipe"]);
			 if($ipsi==$ipei)
			   $show=$_SESSION["ips"];
		     else
		  	   $show=$_SESSION["ips"]."--".$_SESSION["ipe"];
             if ($systemmode == 0)
    		 {
    		  $format = "select v.name,INET_NTOA(u.ip) as ip,u.blocknum from  (select ip_inner as ip,sum(packets_blocked_num) as blocknum from $tablename where (ip_inner between  %u and  %u )  and  (logtime between %u and %u) group by ip_inner order by blocknum desc limit 0 ,9) as u left join (select name,bindip from useraccount) as v on u.ip=v.bindip";
    	     }
    		 else
             {
             $format = "select v.account as name,INET_NTOA(u.ip) as ip,u.blocknum from  (select account_id, ip_inner as ip,sum(packets_blocked_num) as blocknum from $tablename where (ip_inner between %u and %u) and  (logtime between %u and %u) group by account_id order by blocknum desc limit 0 ,9) as u left join (select id,account from useraccount) as v on u.account_id=v.id";
         	 }
			 $arr = sprintf($format,$tablename,$ipsi,$ipei,$dates,$datee);
			break;
    	    default:
    		return;
    }
     $arr = $db->fetchRows($arr);
   
?>
  	<br>
        <div class="common" style="width: 800px">
        <div class="common_title"> 
        <div class="common_title_left"></div> 
        <div class="common_title_word"><?php echo $show ?>  Top10阻挡Ip  <?php list($year,$month,$day)=explode('-',$_SESSION["date1"]);$tmp=mktime(0,0,0,$month+1,0,$year);echo $ds.'至';if($datee>$tmp){echo date("Y-m-d",$tmp);}else{echo $de;}?></div> 
        <div class="common_title_right"></div> 
        </div>	
	
<table>
<thead>            
		<tr><td>ip(<?php echo $mode ?>)</td><th>被挡包数</th></tr>
</thead>
<tbody>
	<?php foreach($arr as $value){ ?>
	<tr>
		<th><?php if(isset($value['name'])) echo $value['ip']."(".$value['name'].")"; else echo $value['ip']; ?></th>
		<td><?php echo $value['blocknum']; ?></td>
   </tr>	
	<?php  } unset($arr); 
	 
   

    ?>
    

</body>
</html>