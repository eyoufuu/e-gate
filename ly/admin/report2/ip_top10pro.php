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

   $ipsi = ip2long($_REQUEST['ip']);
   $ipei = ip2long($_REQUEST['ip']);
			
    $show=$_REQUEST['ip'];
//$arr = "select v.name, u.upflow,u.downflow,u.updownflow from (select pro_id ,floor(sum(upflow)/1024) as upflow,floor(sum(downflow)/1024) as downflow, floor(sum(upflow+downflow)/1024) as updownflow  from $tablename where logtime between  $dates and $datee  group by pro_id order by updownflow desc limit 0 ,9) as u  left join (select proid,name from procat) as v  on u.pro_id=v.proid ";
  $format = "select v.name, u.upflow,u.downflow,u.updownflow from (select pro_id ,floor(sum(upflow)/1024) as upflow,floor(sum(downflow)/1024) as downflow, floor(sum(upflow+downflow)/1024) as updownflow from %s  where (ip_inner between %u and %u) and (logtime between  %u and %u) group by pro_id order by updownflow desc limit 0 ,9) as u  left join (select proid,name from procat) as v on u.pro_id=v.proid ";
	$arr = sprintf($format,$tablename,$ipsi,$ipei,$dates,$datee);
 // echo $arr;		
   $arr = $db->fetchRows($arr);
	   $altogether=0;
		foreach($arr as $value){
                       $altogether=$altogether+(INT)$value['updownflow'];
				      }
    ?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>top10ipflow</title>
	    <!-- 1. Add these JavaScript inclusions in the head of your page -->
		<script type="text/javascript" src="./js/jquery.min.js"></script>
		<script type="text/javascript" src="./js/highcharts.js"></script>
		<!--[if IE]>
			<script type="text/javascript" src="./js/excanvas.compiled.js"></script>
		<![endif]-->
		<!-- 2. Add the JavaScript to initialize the chart on document ready -->
		<style type="text/css" title="currentStyle">
			@import "./css/demo_page.css";
			@import "./css/demo_table.css";
		</style>
		<script type="text/javascript" language="javascript" src="./js/jquery.js"></script>
		<script type="text/javascript" language="javascript" src="./js/jquery.dataTables.js"></script>
		
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
                          $return_array[$i][0]=$value['name'];
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
			
			} );
		</script>

</head>
	<body id="dt_example">
	<div id="container">
			    <div class="full_width big">
			 <i>Top协议流量</i> 
			</div>
			<h1>选择的时间范围：<?php list($year,$month,$day)=explode('-',$ds);$tmp=mktime(0,0,0,$month+1,0,$year);echo $ds.'至';if($datee>$tmp){echo date("Y-m-d",$tmp);}else{echo $de;} ?> &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; 选择的用户：<?php echo $show ?>  </h1>				
			
		<!-- 3. Add the container -->
		<div id="containers" style="width: 800px; height: 400px; margin: 0 auto"></div>
	<div id="demo">
   <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
			
			<th>协议名称</th>
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
	
			
			<td class="left"><?php echo  $value['name']  ?></td>
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
