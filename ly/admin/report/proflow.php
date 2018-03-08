<!DOCTYPE html>
<?php
   require_once('_inc.php');
?>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>topproflow</title>	
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
    		$arr = "select procat.name, u.upflow,u.downflow,u.updownflow from procat ,(select pro_id ,floor(sum(upflow)/1024) as upflow,floor(sum(downflow)/1024) as downflow, floor(sum(upflow+downflow)/1024) as updownflow  from $tablename where logtime between  $dates and $datee  group by pro_id order by updownflow desc limit 0 ,9) as u where u.pro_id=procat.proid ";
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
		        $arr = "select procat.name, u.upflow,u.downflow,u.updownflow from procat ,(select pro_id ,floor(sum(upflow)/1024) as upflow,floor(sum(downflow)/1024) as downflow, floor(sum(upflow+downflow)/1024) as updownflow  from $tablename where (ip_inner=$accountid) and (logtime between  $dates and $datee) group by pro_id order by updownflow desc limit 0 ,9) as u where u.pro_id=procat.proid ";
            } 
           else
            {
              $arr = "select id from useraccount where name= '$username'";
		        $arr = $db->fetchRow($arr);
		        $accountid= $arr["id"];
		        $arr = "select procat.name, u.upflow,u.downflow,u.updownflow from procat ,(select pro_id ,floor(sum(upflow)/1024) as upflow,floor(sum(downflow)/1024) as downflow, floor(sum(upflow+downflow)/1024) as updownflow from $tablename where (account_id=$accountid) and (logtime between  $dates and $datee ) group by pro_id order by updownflow desc limit 0 ,9) as u where u.pro_id=procat.proid ";
             }    
        	break;
    	case "3":
    		$account = $_SESSION["account"];
            $show=$account;      		
    		$arr = "select id from useraccount where account= '$account'";
		    $arr = $db->fetchRow($arr);
		    $accountid= $arr["id"];
		    $arr = "select procat.name, u.upflow,u.downflow,u.updownflow from procat ,(select pro_id ,floor(sum(upflow)/1024) as upflow,floor(sum(downflow)/1024) as downflow, floor(sum(upflow+downflow)/1024) as updownflow from $tablename where (account_id=$accountid) and (logtime between  $dates and $datee ) group by pro_id order by updownflow desc limit 0 ,9) as u where u.pro_id=procat.proid ";
    		break;
    	case "4":
			$ipsi = ip2long($_SESSION["ips"]);
			$ipei = ip2long($_SESSION["ipe"]);
			if($ipsi==$ipei)
			   $show=$_SESSION["ips"];
		    else
		  	   $show=$_SESSION["ips"]."--".$_SESSION["ipe"];
			$format = "select procat.name, u.upflow,u.downflow,u.updownflow from procat ,(select pro_id ,floor(sum(upflow)/1024) as upflow,floor(sum(downflow)/1024) as downflow, floor(sum(upflow+downflow)/1024) as updownflow from %s  where (ip_inner between %u and %u) and (logtime between  %u and %u) group by pro_id order by updownflow desc limit 0 ,9) as u where u.pro_id=procat.proid ";
			$arr = sprintf($format,$tablename,$ipsi,$ipei,$dates,$datee);
			break;
    	default:
    		return;
    }
   $arr = $db->fetchRows($arr);
   
?>
  <div class="common" style="width: 800px">
        <div class="common_title"> 
        <div class="common_title_left"></div> 
        <div class="common_title_word"><?php echo $show ?>  Top10协议流量  <?php list($year,$month,$day)=explode('-',$_SESSION["date1"]);$tmp=mktime(0,0,0,$month+1,0,$year);echo $ds.'至';if($datee>$tmp){echo date("Y-m-d",$tmp);}else{echo $de;}?></div> 
        <div class="common_title_right"></div> 
        </div>	 
<table>

<thead>            
		<tr><td>协议名称</td><th>上行流量(KB)</th><th>下行流量(KB)</th><th>总流量(KB)</th></tr>
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