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
    	   $arrblock = "select host, count(pass) as times from $tablename where (pass=0) and (get_type=1) and (logtime between $dates and $datee) group by host order by times desc limit 0 ,9";
           $show="全部";
    		break;
    	case "2":
    	    
    		$username =  $_SESSION["username"];
    		$show=$username;
    		   if ($systemmode == 0)
            {
              $arr = "select bindip from useraccount where name= '$username'";
		        $arr = $db->fetchRow($arr);
		        $accountid= $arr["bindip"];
		        $arrblock = "select host, count(pass) as times from $tablename where (ip_inner=$accountid) and (pass=0) and (get_type=1) and (logtime between $dates and $datee )  group by host order by times desc limit 0 ,9";
            } 
           else
            {
                $arr = "select id from useraccount where name= '$username'";
		        $arr = $db->fetchRow($arr);
		        $accountid= $arr["id"];
		        $arrblock = "select host, count(pass) as times from $tablename  where (account_id=$accountid) and (pass=0) and (get_type=1) and (logtime between $dates and $datee ) group by host order by times desc limit 0 ,9";
             }     
    		
		    		    
    		break;
    	case "3":
    		$account = $_SESSION["account"];
    	    $show=$account;
        	$arr = "select id from useraccount where account= '$account'";
		    $arr = $db->fetchRow($arr);
		    $accountid= $arr["id"];
		    $arrblock = "select host, count(pass) as times from $tablename where (ip_inner=$accountid) and (pass=0) and (get_type=1) and (logtime between $dates and $datee )  group by host order by times desc limit 0 ,9";	
    		break;
    	case "4":
			$ipsi = ip2long($_SESSION["ips"]);
			$ipei = ip2long($_SESSION["ipe"]);
			if($ipsi==$ipei)
			   $show=$_SESSION["ips"];
		    else
		  	   $show=$_SESSION["ips"]."--".$_SESSION["ipe"];
            $format = "select host, count(pass) as times from %s where (ip_inner between %u and %u) and (pass=0) and (get_type=1) and (logtime between %u and %u) group by host order by times desc limit 0 ,9";
			$arrblock = sprintf($format,$tablename,$ipsi,$ipei,$dates,$datee);    
               echo $arrblock;			   
    		break;
    	    default:
    		return;
    }
  ?>
         <div class="common" style="width: 800px">
        <div class="common_title"> 
        <div class="common_title_left"></div> 
        <div class="common_title_word"><?php echo $show ?>  被阻挡最多的网站 <?php list($year,$month,$day)=explode('-',$_SESSION["date1"]);$tmp=mktime(0,0,0,$month+1,0,$year);echo $ds.'至';if($datee>$tmp){echo date("Y-m-d",$tmp);}else{echo $de;}?></div> 
        <div class="common_title_right"></div> 
        </div>
<?php
  $arrblock = $db->fetchRows($arrblock);
?>
<table>

<thead>            
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