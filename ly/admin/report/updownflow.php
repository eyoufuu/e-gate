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
   if($ds==$de)
    $timeshow="小时";
   else
    $timeshow="日";
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
		    if($dates == $datee)
              {              
			   $datee=$datee+86400; 
	           $arr = "select hour(FROM_UNIXTIME(logtime)) as logtime,floor(sum(upflow)/1024) as upflow,floor(sum(downflow)/1024) as downflow, floor(sum(upflow+downflow)/1024) as updownflow ,hour(FROM_UNIXTIME(logtime)) as hours from $tablename where logtime between $dates and $datee group by hours ";
              }	    
			else 
			  {
			   $arr = "select day(FROM_UNIXTIME(logtime)) as logtime,floor(sum(upflow)/1024) as upflow,floor(sum(downflow)/1024) as downflow,floor(sum(upflow+downflow)/1024) as updownflow,day(FROM_UNIXTIME(logtime)) as days from  $tablename where logtime between $dates and $datee group by days ";
    		  }
			
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
                if($dates == $datee) 
                  {
				    $datee=$datee+86400; 
					//echo $dates."<br>";
					//echo $datee;
				    $arr = "select hour(FROM_UNIXTIME(logtime)) as logtime,floor(sum(upflow)/1024) as upflow,floor(sum(downflow)/1024) as downflow, floor(sum(upflow+downflow)/1024) as updownflow ,hour(FROM_UNIXTIME(logtime)) as hours from $tablename where (ip_inner=$accountid) and (logtime between  $dates and $datee) group by hours ";
				  }	
                else
                   {
				   $arr = "select day(FROM_UNIXTIME(logtime)) as logtime,floor(sum(upflow)/1024) as upflow,floor(sum(downflow)/1024) as downflow,floor(sum(upflow+downflow)/1024) as updownflow,day(FROM_UNIXTIME(logtime)) as days from  $tablename where (ip_inner=$accountid) and (logtime between $dates and $datee) group by days ";
				   }				
	        } 
           else
            {
              $arr = "select id from useraccount where name= '$username'";
		        $arr = $db->fetchRow($arr);
		        $accountid= $arr["id"];
		      if($dates == $datee) 
                  {
				    $datee=$datee+86400; 
					//echo $dates."<br>";
					//echo $datee;
				    $arr = "select hour(FROM_UNIXTIME(logtime)) as logtime,floor(sum(upflow)/1024) as upflow,floor(sum(downflow)/1024) as downflow, floor(sum(upflow+downflow)/1024) as updownflow,hour(FROM_UNIXTIME(logtime)) as hours from $tablename where (ip_inner=$accountid) and (logtime between  $dates and $datee) group by hours ";
				  }	
                else
                   {
				   $arr = "select day(FROM_UNIXTIME(logtime)) as logtime,floor(sum(upflow)/1024) as upflow,floor(sum(downflow)/1024) as downflow,floor(sum(upflow+downflow)/1024) as updownflow,day(FROM_UNIXTIME(logtime)) as days from  $tablename where (ip_inner=$accountid) and (logtime between $dates and $datee) group by days ";
				   }	  
				
	        }    
      		break;
     	case "3":
    		   $account = $_SESSION["account"];
    		   $show=$account;
    		   $arr = "select id from useraccount where account= '$account'";
		       $arr = $db->fetchRow($arr);
		       $accountid= $arr["id"];
		      if($dates == $datee) 
                  {
				    $datee=$datee+86400; 
					//echo $dates."<br>";
					//echo $datee;
				    $arr = "select hour(FROM_UNIXTIME(logtime)) as logtime,floor(sum(upflow)/1024) as upflow,floor(sum(downflow)/1024) as downflow, floor(sum(upflow+downflow)/1024) as updownflow ,hour(FROM_UNIXTIME(logtime)) as hours from $tablename where (account_id=$accountid) and (logtime between  $dates and $datee) group by hours ";
				  }	
                else
                   {
				   $arr = "select day(FROM_UNIXTIME(logtime)) as logtime,floor(sum(upflow)/1024) as upflow,floor(sum(downflow)/1024) as downflow,floor(sum(upflow+downflow)/1024) as updownflow,day(FROM_UNIXTIME(logtime)) as days from  $tablename where (account_id=$accountid) and (logtime between $dates and $datee) group by days ";
				   }	  
    		break;
    	case "4":
			  
   			  $ipsi = ip2long($_SESSION["ips"]);
			  $ipei = ip2long($_SESSION["ipe"]);
			   if($ipsi==$ipei)
			     $show=$_SESSION["ips"];
				else
		  	     $show=$_SESSION["ips"]."--".$_SESSION["ipe"];
			   if($dates == $datee) 
                  {
				    $datee=$datee+86400; 
     			    $format = "select hour(FROM_UNIXTIME(logtime)) as logtime,floor(sum(upflow)/1024) as upflow,floor(sum(downflow)/1024) as downflow, floor(sum(upflow+downflow)/1024) as updownflow ,hour(FROM_UNIXTIME(logtime)) as hours from  %s where (ip_inner between %u and %u) and (logtime between  %u and %u) group by hours ";
        	      }	
                else
                   {
				    $format = "select day(FROM_UNIXTIME(logtime)) as logtime,floor(sum(upflow)/1024) as upflow,floor(sum(downflow)/1024) as downflow,floor(sum(upflow+downflow)/1024) as updownflow,day(FROM_UNIXTIME(logtime)) as days from  %s where (logtime between  %u and %u) and (ip_inner between %u and %u) group by days ";
	               }	  
				   
				   $arr = sprintf($format,$tablename,$dates,$datee,$ipsi,$ipei);
	 		    
        	break;
          	 default:
    	   return;
    }
   $arr = $db->fetchRows($arr);
 ?>
<!--以下表格要用php生成就可以了-->
        <div class="common" style="width: 800px">
        <div class="common_title"> 
        <div class="common_title_left"></div> 
        <div class="common_title_word"><?php echo $show ?>  上下行流量  <?php list($year,$month,$day)=explode('-',$_SESSION["date1"]);$tmp=mktime(0,0,0,$month+1,0,$year);echo $ds.'至';if($datee>$tmp){echo date("Y-m-d",$tmp);}else{echo $de;} ?></div> 
        <div class="common_title_right"></div> 
        </div>	
<table>

 <thead>            
		<tr><td>时间(<?php echo $timeshow ?>)</td><th>上行流量(KB)</th><th>下行流量(KB)</th><th>总流量(KB)</th></tr>
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