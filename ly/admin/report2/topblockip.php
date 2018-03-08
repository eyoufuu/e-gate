<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<?php
   require_once('_inc.php');
   session_start();
   if(!isset($_SESSION["date1"]))
       {
	     header("Location:./condition.php"); 
         return;
	   }
   $ds = $_SESSION["date1"];
   $de = $_SESSION["date2"];
   $symbol= $_SESSION["symbol"]; 
   
   list($year,$month,$day)=explode('-',$ds);
   $date_ym = $year.$month;
   $tablename=$date_ym."flowdata";
	
   $dates = strtotime($ds);
   $datee = strtotime($de);
   //if($dates==$datee)
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
               $arr = "select v.name,INET_NTOA(u.ip_inner) as ip,u.blocknum from (select ip_inner,sum(packets_blocked_num) as blocknum from $tablename where (ip_inner=$bindip) and (logtime between  $dates and $datee ) group by ip_inner ) as u left join (select name,bindip from useraccount) as v on u.ip_inner=v.bindip";
    		} else
            {
    		      $arr = "select id from useraccount where name= '$username'";
                  $arr = $db->fetchRow($arr);
		          $accountid= $arr["id"];
		          $arr = "select v.account as name,INET_NTOA(u.ip_inner) as ip,u.blocknum from (select account_id ,ip_inner,sum(packets_blocked_num) as blocknum from $tablename where (account_id=$accountid) and (logtime between $dates and $datee) group by ip_inner) as u left join (select account,id from useraccount) as v on u.account_id=v.id";
		          
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
    		   $format = "select v.name,INET_NTOA(u.ip) as ip,u.blocknum from  (select ip_inner as ip,sum(packets_blocked_num) as blocknum from %s where (ip_inner between  %u and  %u )  and  (logtime between %u and %u) group by ip_inner order by blocknum desc limit 0 ,9) as u left join (select name,bindip from useraccount) as v on u.ip=v.bindip";
    	     }
    		 else
             {
               $format = "select v.account as name,INET_NTOA(u.ip) as ip,u.blocknum from  (select account_id, ip_inner as ip,sum(packets_blocked_num) as blocknum from %s where (ip_inner between %u and %u) and  (logtime between %u and %u) group by account_id order by blocknum desc limit 0 ,9) as u left join (select id,account from useraccount) as v on u.account_id=v.id";
         	 }
			 $arr = sprintf($format,$tablename,$ipsi,$ipei,$dates,$datee);
			break;
    	    default:
    		return;
    }
	   //echo $arr;
       $arr = $db->fetchRows($arr);
	   
	   $altogether=0;
		foreach($arr as $value){
                       $altogether=$altogether+(INT)$value['blocknum'];
				      }
    ?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>top10ipflow</title>
	    <!-- 1. Add these JavaScript inclusions in the head of your page -->
		<script type="text/javascript" src="./js/jquery.min.js"></script>
		<script type="text/javascript" src="./js/highcharts.js"></script>
		<style type="text/css" title="currentStyle">
			@import "./css/demo_page.css";
			@import "./css/demo_table.css";
		</style>
		<script type="text/javascript" language="javascript" src="./js/jquery.js"></script>
		<script type="text/javascript" language="javascript" src="./js/jquery.dataTables.js"></script>
		<!--[if IE]>
			<script type="text/javascript" src="./js/excanvas.compiled.js"></script>
		<![endif]-->
		<!-- 2. Add the JavaScript to initialize the chart on document ready -->
		<script type="text/javascript">
		var chart;
		$(document).ready(function() {
			chart = new Highcharts.Chart({
				chart: {
					renderTo: 'containers',
					margin: [50, 200, 60, 170]
				},
				title: {
					text: ''
				},
				plotArea: {
					shadow: null,
					borderWidth: null,
					backgroundColor: null
				},
				tooltip: {
					formatter: function() {
						return '<b>'+ this.point.name +'</b>: '+ this.y +' %';
					}
				},
				plotOptions: {
					pie: {
						allowPointSelect: true,
						dataLabels: {
							enabled: true,
							formatter: function() {
								if (this.y > 5) return this.point.name;
							},
							color: 'white',
							style: {
								font: '13px Trebuchet MS, Verdana, sans-serif'
							}
						}
					}
				},
				legend: {
					layout: 'vertical',
					style: {
						left: 'auto',
						bottom: 'auto',
						right: '50px',
						top: '100px'
					}
				},
			        series: [{
					type: 'pie',
					name: 'Browser share',
					data: <?php
	                      				      
						  $i=0;
					      foreach($arr as $value){
						  if($value['name']!="")
                           $return_array[$i][0]=$value['ip']."(".$value['name'].")";
						  else
                            $return_array[$i][0]=$value['ip'];      						  
						   $temp=(float)$value['blocknum']/$altogether;
						  //$temp=number_format($temp, 2,'.','');
                           $return_array[$i][1]=(float)number_format($temp, 3,'.','')*100;
						  
				          $i++;                         
						 }					  
				     $sdata = json_encode($return_array);
	                  echo $sdata;                 
					 ?> 
               }]
			});
			
			
		});
		</script>
		<script type="text/javascript" charset="utf-8">
			
		$(document).ready(function() {
				$('#example').dataTable();
			} );
		</script>

		
     
</head>
	<body id="dt_example">
<div id="container">
           <div class="full_width big">
			   <i>top10阻挡ip报表</i> 
			</div>
			<h1>选择的时间范围：<?php list($year,$month,$day)=explode('-',$ds);$tmp=mktime(0,0,0,$month+1,0,$year);echo $ds.'至';if($datee>$tmp){echo date("Y-m-d",$tmp);}else{echo $de;} ?> &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; 选择的用户：<?php echo $show ?>  </h1>
		<!-- 3. Add the container -->
		<div id="containers" style="width: 800px; height: 400px; margin: 0 auto"></div>
	
<div id="demo">
   <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
			<th>IP</th>
			<th>用户名</th>
			<th>阻挡包数</th>
		
		</tr>
	</thead>
	<tbody>
	  <?php
	    //$odd="true";
	   foreach($arr as $value){
	    
	  ?>
		 <tr>
			<td><?php echo $value['ip'] ?></td>
			<td class="center"><?php echo $value['name'] ?></td>
			<td class="center"><?php echo  $value['blocknum'] ?></td>
		</tr>
		<?php
          }		
		?>
		
	</tbody>
	
  </table>
 </div>
		<div class="spacer"></div>
		<div id="footer" style="text-align:center;">
		  <span style="font-size:10px;">Copyright &copy;2010 Powered by <a href="http://www.lysafe365.com/">凌屹信息科技</a> <?php echo date('Y/m/d') ?></span>
		</div>
</div>
		
	</body>
</html>
