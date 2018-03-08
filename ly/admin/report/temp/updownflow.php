<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "  http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
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
      	enhance({
	      	  loadScripts: [
				'./mychart/excanvas.js',
				'./mychart/jquery.min.js',
				'./mychart/visualize.jQuery.js',
				'./mychart/example.js'
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
    		$arr = "call updownflow_all('$tablename',$dates,$datee)";
    		break;
    	case "2":
    	    
    		$username =  $_SESSION["username"];
    		
    		 if ($systemmode == 0)
            {
              $arr = "select bindip from useraccount where name= '$username'";
		        $arr = $db->fetchRow($arr);
		        $accountid= $arr["bindip"];
		        $arr = "call updownflow_id('$tablename',$dates,$datee,$accountid)";
            } 
           else
            {
              $arr = "select id from useraccount where name= '$username'";
		        $arr = $db->fetchRow($arr);
		        $accountid= $arr["id"];
		        $arr = "call updownflowaccount_id('$tablename',$dates,$datee,$accountid)";
             }    
      		break;
     	case "3":
    		   $account = $_SESSION["account"];
    		   $arr = "select id from useraccount where account= '$account'";
		      $arr = $db->fetchRow($arr);
		      $accountid= $arr["id"];
		      $arr = "call updownflowaccount_id('$tablename',$dates,$datee,$accountid)";
    		break;
    	case "4":
			   $ipsi = ip2long($_SESSION["ips"]);
			   $ipei = ip2long($_SESSION["ipe"]);
			   $format = "call updownflow_ips('%s',%u,%u,%u,%u)";
			   $arr = sprintf($format,$tablename,$dates,$datee,$ipsi,$ipei);
    		break;
    	 default:
    	  return;
    }
   $arr = $db->fetchRows($arr);
 ?>
<!--以下表格要用php生成就可以了-->
<table>
 <caption>上下行流量</caption>
 <thead>            
		<tr><td>时间</td><th>上行流量</th><th>下行流量</th><th>总流量</th></tr>
</thead>
<tbody>
	<?php foreach($arr as $value){?>
	<tr>
		<th><?php echo $value['logtime']; ?></th>
		<td><?php echo $value['upflow']; ?></td>
		<td><?php echo $value['downflow']; ?></td>
		<td><?php echo $value['updownflow']; ?></td>
	</tr>	
	<?php } unset($arr); 
  ?>
</tbody>
</table>
</body>
</html>