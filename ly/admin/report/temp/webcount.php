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
				'./mychart/webcount.js'
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
   $tablename=$date_ym."web";
	
   $dates = strtotime($ds);
   $datee = strtotime($de);
   
   $arr = "select systemmode from globalpara";
   $arr = $db->fetchRow($arr);
   $systemmode= $arr["systemmode"];
  
   
  
    switch($symbol)
    {
    	case "1":
    		$arr = "call top10passweb_all('$tablename',$dates,$datee)";
         $arrblock = "call top10blockweb_all('$tablename',$dates,$datee)";

    		break;
    	case "2":
    	    
    		$username =  $_SESSION["username"];
    		  if ($systemmode == 0)
            {
              $arr = "select bindip from useraccount where name= '$username'";
		        $arr = $db->fetchRow($arr);
		        $accountid= $arr["bindip"];
		        $arr = "call top10passweb_id('$tablename',$dates,$datee,$accountid)";
		        $arrblock = "call top10blockweb_id('$tablename',$dates,$datee,$accountid)";
            } 
           else
            {
              $arr = "select id from useraccount where name= '$username'";
		        $arr = $db->fetchRow($arr);
		        $accountid= $arr["id"];
		        $arr = "call top10passwebaccount_id('$tablename',$dates,$datee,$accountid)";
		        $arrblock = "call top10blockwebaccount_id('$tablename',$dates,$datee,$accountid)";
             }     
    		
		    		    
    		break;
    	case "3":
    		$account = $_SESSION["account"];
    		$arr = "select id from useraccount where account= '$account'";
		    $arr = $db->fetchRow($arr);
		    $accountid= $arr["id"];
		    $arr = "call top10passwebaccount_id('$tablename',$dates,$datee,$accountid)";
          $arrblock = "call top10blockwebaccount_id('$tablename',$dates,$datee,$accountid)";    		
    		break;
    	case "4":
			$ipsi = ip2long($_SESSION["ips"]);
			$ipei = ip2long($_SESSION["ipe"]);
			$format = "call top10passweb_ips('%s',%u,%u,%u,%u)";
			$arr = sprintf($format,$tablename,$dates,$datee,$ipsi,$ipei);
         $formatblock = "call top10blockweb_ips('%s',%u,%u,%u,%u)";
			$arrblock = sprintf($format,$tablename,$dates,$datee,$ipsi,$ipei);    		
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
        <div class="common_title_word">网页过滤报表</div> 
        <div class="common_title_right"></div> 
        </div>	
	<br>
	<br>
	<p class= "body_title1" >Top10访问最多的网站</p>
	<p class= "body_title2" ><?php list($year,$month,$day)=explode('-',$_SESSION["date1"]);$tmp=mktime(0,0,0,$month+1,0,$year);echo $ds.'至';if($datee>$tmp){echo date("Y-m-d",$tmp);}else{echo $de;}?></p>
<!--以下表格要用php生成就可以了-->

<table>
<caption>Top10访问最多的网站</caption>
<thead>            
		<tr><td>网站host</td><th>次数</th></tr>
</thead>
<tbody>
	<?php foreach($arr as $value){ ?>
	<tr>
		<th><?php echo $value['host']; ?></th>
		<td><?php echo $value['times']; ?></td>
	</tr>	
	<?php  } unset($arr); 
	
  
    ?>
    

   
	<br>
	<br>
	<!--以下表格要用php生成就可以了-->

<?php
  $arrblock = $db->fetchRows($arrblock);
?>
<table>
<caption>Top10被阻挡最多的网站</caption>
<thead>            
		<tr><td>网站host</td><th>次数</th></tr>
</thead>
<tbody>
	<?php foreach($arrblock as $value){ ?>
	<tr>
		<th><?php echo $value['host']; ?></th>
		<td><?php echo $value['times']; ?></td>
	</tr>	
	<?php  } unset($arrblock); 
	

    ?>
    


</body>
</html>