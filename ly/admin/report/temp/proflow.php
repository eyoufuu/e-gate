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
   
   $arr = "select systemmode from globalpara";
   $arr = $db->fetchRow($arr);
   $systemmode= $arr["systemmode"];
  
   
  
    switch($symbol)
    {
    	case "1":
    		$arr = "call top10passpro_all('$tablename',$dates,$datee)";
    		
    		break;
    	case "2":
    	    
    	     $username =  $_SESSION["username"];
    		  if ($systemmode == 0)
            {
              $arr = "select bindip from useraccount where name= '$username'";
		        $arr = $db->fetchRow($arr);
		        $accountid= $arr["bindip"];
		        $arr = "call top10passpro_id('$tablename',$dates,$datee,$accountid)";
            } 
           else
            {
              $arr = "select id from useraccount where name= '$username'";
		        $arr = $db->fetchRow($arr);
		        $accountid= $arr["id"];
		        $arr = "call top10passproaccount_id('$tablename',$dates,$datee,$accountid)";
             }    
    		 
    		break;
    	case "3":
    		$account = $_SESSION["account"];
    		$arr = "select id from useraccount where account= '$account'";
		    $arr = $db->fetchRow($arr);
		    $accountid= $arr["id"];
		    $arr = "call top10passproaccount_id('$tablename',$dates,$datee,$accountid)";
    		break;
    	case "4":
			$ipsi = ip2long($_SESSION["ips"]);
			$ipei = ip2long($_SESSION["ipe"]);
			$format = "call top10passpro_ips('%s',%u,%u,%u,%u)";
			$arr = sprintf($format,$tablename,$dates,$datee,$ipsi,$ipei);
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
        <div class="common_title_word">Top10协议流量</div> 
        <div class="common_title_right"></div> 
        </div>	
	<br>
	<br>
	<p class= "body_title1" >Top10协议流量</p>
	<p class= "body_title2" ><?php list($year,$month,$day)=explode('-',$_SESSION["date1"]);$tmp=mktime(0,0,0,$month+1,0,$year);echo $ds.'至';if($datee>$tmp){echo date("Y-m-d",$tmp);}else{echo $de;}?></p>
<!--以下表格要用php生成就可以了-->

<table>
<caption>Top10协议流量</caption>
<thead>            
		<tr><td>协议名称</td><th>上行流量</th><th>下行流量</th><th>总流量</th></tr>
</thead>
<tbody>
	<?php foreach($arr as $value){ ?>
	<tr>
		<th><?php echo $value['name']; ?></th>
		<td><?php echo $value['upflow']; ?></td>
		<td><?php echo $value['downflow']; ?></td>
	   <td><?php echo $value['updownflow']; ?></td>
	</tr>	
	<?php  } unset($arr); 
	
	 
   

    ?>
    

</body>
</html>