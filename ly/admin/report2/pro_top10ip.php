<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<?php
   require_once('_inc.php');
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
   //if($dates==$datee)
     $datee=$datee+86400; 
   $arr = "select systemmode from globalpara";
   $arr = $db->fetchRow($arr);
   $systemmode= $arr["systemmode"];
   if ($systemmode == 0)
    $mode='用户';
   else
    $mode='帐号';  
	
  $proname=$_REQUEST['name'];

  $arr = "select proid from procat where name='".$proname."'";

  $arr = $db->fetchRow($arr);
  $proid = $arr["proid"]; 

 
	

    switch($symbol)
    {
    	case "1":
		   if ($systemmode == 0)
            {    		
              $arr = "select v.name,INET_NTOA(u.ip) as ip,u.upflow,u.downflow,u.updownflow from (select ip_inner as ip,floor(sum(upflow)/1024) as upflow,floor(sum(downflow)/1024) as downflow, floor(sum(upflow+downflow)/1024) as updownflow from  $tablename where (logtime between $dates and $datee) and (pro_id=$proid) group by ip_inner order by updownflow desc limit 0 ,9) as u left join  (select name ,bindip from useraccount ) as v on u.ip=v.bindip ";
             
            } else
            {
    		   $arr = "select v.account as name,INET_NTOA(u.ip) as ip,u.upflow,u.downflow,u.updownflow from (select account_id,ip_inner as ip,floor(sum(upflow)/1024) as upflow,floor(sum(downflow)/1024) as downflow,  floor(sum(upflow+downflow)/1024) as updownflow from $tablename where (logtime between $dates and $datee) and (pro_id=$proid) group by  account_id order by updownflow desc limit 0 ,9) as u left join  (select  account, id from useraccount ) as v on u.account_id=v.id";
              
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
		      $bindip = $arr["bindip"];
			  $arr = "select v.name,INET_NTOA(u.ip) as ip,u.upflow,u.downflow,u.updownflow from (select ip_inner as ip,floor(sum(upflow)/1024) as upflow,floor(sum(downflow)/1024) as downflow, floor(sum(upflow+downflow)/1024) as updownflow from  $tablename where (ip_inner=$bindip) and (logtime between $dates and $datee) and (pro_id=$proid) group by ip_inner order by updownflow desc limit 0 ,9) as u left join  (select name ,bindip from useraccount ) as v on u.ip=v.bindip ";
              
                 
            } else
            {
    		      $arr = "select id from useraccount where name= '$username'";
                  $arr = $db->fetchRow($arr);
		          $accountid= $arr["id"];
                  $arr = "select v.name,INET_NTOA(u.ip) as ip,u.upflow,u.downflow,u.updownflow from (select ip_inner as ip,floor(sum(upflow)/1024) as upflow,floor(sum(downflow)/1024) as downflow, floor(sum(upflow+downflow)/1024) as updownflow from  $tablename where (account_id=$accountid) and (logtime between $dates and $datee) and (pro_id=$proid) group by ip_inner order by updownflow desc limit 0 ,9) as u left join  (select name ,bindip from useraccount ) as v on u.ip=v.bindip ";
                     
				
           	}
	 		break;
    	case "3":
    		$account = $_SESSION["account"];
            $show=$account;     		
    		$arr = "select id from useraccount where account= '$account'";
		    $arr = $db->fetchRow($arr);
		    $accountid= $arr["id"];
		    $arr = "select v.account as name,INET_NTOA(u.ip) as ip,u.upflow,u.downflow,u.updownflow from (select ip_inner as ip,account_id,floor(sum(upflow)/1024) as upflow,floor(sum(downflow)/1024) as downflow, floor(sum(upflow+downflow)/1024) as updownflow from $tablename where (account_id=$accountid) and (pro_id=$proid) and (logtime between $dates and $datee) group by ip_inner order by updownflow desc limit 0 ,9) as u  left join (select id,account from useraccount) as v on u.account_id=v.id ";
                     
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
    		  $format = "select v.name,INET_NTOA(u.ip) as ip,u.upflow,u.downflow,u.updownflow from  (select ip_inner as ip,floor(sum(upflow)/1024) as upflow,floor(sum(downflow)/1024) as downflow, floor(sum(upflow+downflow)/1024) as updownflow from %s where (ip_inner between %u and %u) and  (logtime between %u and %u) and (pro_id=%u) group by ip_inner order by updownflow desc limit 0 ,9) as u left join (select name,bindip from useraccount) as v on u.ip=v.bindip";
    		  $arr = sprintf($format,$tablename,$ipsi,$ipei,$dates,$datee,$proid);
                    		
			 }
    		else
          {
            $format = "select v.account as name,INET_NTOA(u.ip) as ip,u.upflow,u.downflow,u.updownflow from  (select ip_inner as ip,account_id,floor(sum(upflow)/1024) as upflow,floor(sum(downflow)/1024) as downflow,  floor(sum(upflow+downflow)/1024) as updownflow from  %s where (ip_inner between %u and %u) and  (logtime between  %u and %u) and (pro_id=%u) group by account_id order by updownflow desc limit 0 ,9) as u left join (select account,id from useraccount) as v on u.account_id=v.id";
            $arr = sprintf($format,$tablename,$ipsi,$ipei,$dates,$datee,$proid);
			
    	  }
		 break;
       	 default:
         return;
    }
  
       $arr = $db->fetchRows($arr);
       $altogether=0;
		foreach($arr as $value){
                       $altogether=$altogether+(float)$value['updownflow'];
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
                          //$return_array[$i][0]=$value['ip']."(".$value['name'].")";
						   if($value['name']!="")
                           $return_array[$i][0]=$value['ip']."(".$value['name'].")";
						  else
						   $return_array[$i][0]=$value['ip'];
						  $temp=(float)$value['updownflow']/$altogether;
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
				
				 function get(ip)
		          {
				   window.location.href="test.php?ip="+ip;
		          }
			} );
		</script>
		<script type="text/javascript" charset="utf-8">
		   
				
				 function get(ip)
		          {
				   window.location.href="ip_top10pro.php?ip="+ip;
		          }
		
		</script>
          
</head>
	<body id="dt_example">
	<?php
	    
	?>
	<div id="container">
		 <div class="full_width big">
			 <i><?php echo $proname ?>的Topip</i> 
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
			<th>上行流量(KB)</th>
			<th>下行流量(KB)</th>
			<th>上下行流量(KB)</th>
		
		</tr>
	</thead>
	<tbody>
	  <?php
	     $odd="true";
	     foreach($arr as $value){
	  ?>
		<tr class="<?php if($odd=="true") { echo "gradeA"; $odd="false"; }else { echo "gradeB"; $odd="true";} ?> ">
	        <td class="left"><?php echo $value['ip'] ?></td>
			<td class="center"><?php echo  $value['name'] ?></td>
			<td class="center"><?php echo  ceil($value['upflow']/1024) ?></td>
			<td class="center"><?php echo  ceil($value['downflow']/1024) ?></td>
			<td class="center"><?php echo  ceil($value['updownflow']/1024) ?></td>


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
