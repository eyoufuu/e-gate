<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html> 
 <head> 
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
    <title>实时流量分析-网络协议流量前十名</title> 
	<link href="../common/main.css" rel="stylesheet" type="text/css"/>
	<script type="text/javascript" src="../common/common.js"></script>
	<script src="../js/jquery.js" type="text/javascript"></script>
	<script src="../js/jscharts_mb.js" type="text/javascript"></script>
	<script type="text/javascript" src="../js/table_set.js"></script>
	
		<!-- 1. Add these JavaScript inclusions in the head of your page -->
		<script type="text/javascript" src="../js/js_new/jquery.min.js"></script>
		<script type="text/javascript" src="../js/js_new/highcharts.js"></script>

		<style type="text/css" title="currentStyle">
			@import "../js/js_new/demo_page.css";
			@import "../js/js_new/demo_table.css";
		</style>
		<!-- 2. Add the JavaScript to initialize the chart on document ready -->
		<script type="text/javascript">
		var g_id_name;
		jQuery(document).ready(function(){

		      function onDataReceived(id_name)
			  {
				 g_id_name = id_name;
				// alert(g_id_name);
			  }
		      $.ajax({
		            url: "get_pro_number_name.php",
		            method: 'GET',
		            dataType: 'json',
					cache:false,
		            success: onDataReceived
					});
		});
		</script>	
		<script type="text/javascript">
		
		var chart;
		$(document).ready(function() {
			chart = new Highcharts.Chart({
				
				chart: {
					renderTo: 'container',
					margin: [50, 200, 60, 170],
					events: {
					load: function() {
					// set up the updating of the chart each second
					var series = this.series[0];
					function fetchData_pro_table_data(toppro)
					{
						for(var i=0;i<toppro.length;i++)
						{
						    x = i+1;						    
						    var proname = g_id_name[toppro[i][3]];
							$("#data_show_pro_top tr:eq("+x+") td:eq(1)").html(proname);//赋值		
							$("#data_show_pro_top tr:eq("+x+") td:eq(2)").html(Math.round(parseFloat(toppro[i][0])/3072*100)/100+"KB");//上行流量		
							$("#data_show_pro_top tr:eq("+x+") td:eq(3)").html(Math.round(parseFloat(toppro[i][1])/3072*100)/100+"KB");//下行流量		
							$("#data_show_pro_top tr:eq("+x+") td:eq(4)").html(Math.round(parseFloat(toppro[i][2])/3072*100)/100+"KB");//总流量		
						}
					}	
					function fetchData_pro()
					   {
						var colors= [
							     	'#4572A7', 
							     	'#AA4643', 
							     	'#89A54E', 
							     	'#80699B', 
							     	'#3D96AE', 
							     	'#DB843D', 
							     	'#92A8CD', 
							     	'#A47D7C', 
							     	'#B5CA92',
							     	'#BBCCAA'
							     ];
						    function onDataReceived(data_pro)
							{
							    //alert(data_ip_pro.ip);
								var piedata=[];
								var len = data_pro.length;
								if(len==0)
								{
									return;
								}
								var total = 0;
								for(var i=0;i<len;i++)
								{
									total += parseFloat(data_pro[i][2]);
								}
								for(var i=0;i<len;i++)
								{
								   // data_pro[i][0] = Math.floor(parseFloat(data_pro[i][0])/1024*1000)/1000;
								   // data_pro[i][1] = Math.floor(parseFloat(data_pro[i][1])/1024*1000)/1000;
									//data_pro[i][2] = Math.floor(parseFloat(data_pro[i][2])/1024*1000)/1000;
									//data_pro[i][3] = g_id_name[data_pro[i][3]];
									 var name = g_id_name[data_pro[i][3]];
									 if(!name)
										 continue;
									 var data =  Math.round((parseFloat(data_pro[i][2]))/total*10000)/100;
									 piedata.push({name:name,color:colors[i],y:data});			 
								}	
								
								series.setData(piedata,true);
								for(var i=1;i<=10;i++)
								{
									var x = i;
									$("#data_show_pro_top tr:eq("+x+") td:eq(1)").html("");//赋值		
									$("#data_show_pro_top tr:eq("+x+") td:eq(2)").html("");//上行流量		
									$("#data_show_pro_top tr:eq("+x+") td:eq(3)").html("");//下行流量		
									$("#data_show_pro_top tr:eq("+x+") td:eq(4)").html("");//总流量		
								}
								fetchData_pro_table_data(data_pro);
								//chart.redraw();																
								}//onDataReceived end;
						    	$.ajax({
						            url: "ip_pro_detail_data.php?ip=<?php echo $_REQUEST['ip'];?>",
						            method: 'GET',
						            dataType: 'json',
									cache:false,
						            success: onDataReceived
									});
						}
					fetchData_pro();
						setInterval(function() {	
							fetchData_pro();
						}, 3000);
					}
				}
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
				data: [					
					['其他',100]
				]
			}]
				
			});		
		});
		</script>
		
	<script type="text/javascript" src="../js/js_new/highslide-full.min.js"></script>
<script type="text/javascript" src="../js/js_new/highslide.config.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="../js/js_new/highslide.css" />

</head> 
<body> 
<script type="text/javascript">
function longtoip(ip)
{
   var s1 = ip&0xff;
   var s2 = (ip&0xffff)>>8;
   var s3 = (ip>>16)&0xff;
   var s4 = ip>>>24;
   var sip = s1+"."+s2+"."+s3+"."+s4;
   return sip;
}
var ip = <?php echo $_REQUEST['ip']?>;
var val = longtoip(ip);
document.write("<h1>"+val+"协议流量分布</h1>");
</script>
 <!--    <div id = "pro_top_10_Y" >单位:KB</div>
   <div id="pro_top_10" style="padding:5px" align="center"></div>-->  
  		<!-- 3. Add the container -->
		<div id="container" style="width: 100%; height: 350px; margin: 0 auto"></div>
  	<table cellpadding="0" cellspacing="0" border="0"  class="display" id="data_show_pro_top">
	<thead><tr><th align="center">排名号</th><th align="center">协议名称</th><th align="center">上行流量</th><th align="center">下行流量</th><th align="center">总流量</th></tr></thead>
	<tbody>
	<tr class="gradeA"><td align="center">1</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td></tr>
	<tr><td align="center">2</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td></tr>
	<tr class="gradeA"><td align="center">3</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td></tr>
	<tr><td align="center">4</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td></tr>
	<tr class="gradeA"><td align="center">5</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td></tr>
	<tr><td align="center">6</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td></tr>
	<tr class="gradeA"><td align="center">7</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td></tr>
	<tr><td align="center">8</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td></tr>
	<tr class="gradeA"><td align="center">9</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td></tr>
	<tr><td align="center">10</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td></tr>
	</tbody>
	<tfoot><tr><th align="center">&nbsp;</th><th align="center">&nbsp;</th><th align="center">&nbsp;</th><th align="center">&nbsp;</th><th align="center">&nbsp;</th></tr></tfoot>
	</table>
</body> 
</html> 