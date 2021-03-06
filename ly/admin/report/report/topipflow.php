<!DOCTYPE html>
<?php
   require_once('_inc.php');
?>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>updownflow</title>	
	 	<link type="text/css" rel="stylesheet" href="./mychart/demopage.css"/>		
		<script type="text/javascript" src="./mychart/enhance.js"></script>		
		<script type="text/javascript">
		// Run capabilities test
		enhance({
			loadScripts: [
				'./mychart/excanvas.js',
				'./mychart/jquery.min.js',
				'./mychart/visualize.jQuery.js',
				'./mychart/topipflow.js'
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
    	   if ($systemmode == 0)
    		$arr = "call top10ip_updownflow_all('$tablename',$dates,$datee)";
    		else
         $arr = "call top10account_updownflow_all('$tablename',$dates,$datee)";
    	   		
    	break;
    	case "2":
    	    
    		$username =  $_SESSION["username"];
    		 if ($systemmode == 0)
            {    		
              $arr = "select bindip from useraccount where name= '$username'";
              $arr = $db->fetchRow($arr);
		        $bindip = $arr["bindip"];
              $arr = "call top10ip_updownflow_id('$tablename',$dates,$datee,$bindip)";
              $mode = '用户';  
                 
            } else
            {
    		      $arr = "select id from useraccount where name= '$username'";
               $arr = $db->fetchRow($arr);
		         $accountid= $arr["id"];
		         $arr = "call top10account_updownflow_id('$tablename',$dates,$datee,$accountid)";
		          $mode = '用户';   
           	}
		    
		    
		    
    		break;
    	case "3":
    		$account = $_SESSION["account"];
    		$arr = "select id from useraccount where account= '$account'";
		    $arr = $db->fetchRow($arr);
		    $accountid= $arr["id"];
		    $arr = "call top10account_updownflow_id('$tablename',$dates,$datee,$accountid)";
    		break;
    	case "4":
			$ipsi = ip2long($_SESSION["ips"]);
			$ipei = ip2long($_SESSION["ipe"]);
         
         if ($systemmode == 0)
    		{
    		 $format = "call top10ip_updownflow_ips('%s',%u,%u,%u,%u)";
    		 
    		 $arr = sprintf($format,$tablename,$dates,$datee,$ipsi,$ipei);
    		}
    		else
          {
           $format = "call top10account_updownflow_ips('%s',%u,%u,%u,%u)";
          
           $arr = sprintf($format,$tablename,$dates,$datee,$ipsi,$ipei);
    	   		  			
			 }
			
			
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
        <div class="common_title_word">Top10 ip流量</div> 
        <div class="common_title_right"></div> 
        </div>	
	<br>
	<br>
	<p class= "body_title1" >Top10 ip流量</p>
	<p class= "body_title2" ><?php list($year,$month,$day)=explode('-',$_SESSION["date1"]);$tmp=mktime(0,0,0,$month+1,0,$year);echo $ds.'至';if($datee>$tmp){echo date("Y-m-d",$tmp);}else{echo $de;}?></p>
<!--以下表格要用php生成就可以了-->

<table>
<caption>Top10 ip流量</caption>
<thead>            
		<tr><td>ip</td><th>上行流量</th><th>下行流量</th><th>总流量</th><td><?php echo $mode ?></td></tr>
</thead>
<tbody>
	<?php foreach($arr as $value){ ?>
	
	<tr>
		<th><?php echo $value['ip']; ?></th>
		
		<td><?php echo $value['upflow']; ?></td>
		<td><?php echo $value['downflow']; ?></td>
	   	<td><?php echo $value['updownflow']; ?></td>
	   	<td><?php echo $value['name'];?></td>
	</tr>
	<?php  } unset($arr); 
	
	?>  


</html>