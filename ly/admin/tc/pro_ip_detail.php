<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<html> 
 <head> 
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
    <title>实时流量分析-网络IP流量前十名</title> 
	<link href="../common/main.css" rel="stylesheet" type="text/css"/>
	<link rel="stylesheet" href="../common/boxy.css" type="text/css" />
	<script language="javascript"  type="text/javascript" src="../common/common.js"></script>
    <!--[if IE]><script language="javascript" type="text/javascript" src="../js/excanvas.min.js"></script><![endif]--> 
	<script language="javascript" type="text/javascript" src="../js/jquery.js" ></script>
    <script language="javascript" type="text/javascript" src="../js/jquery.flot.js"></script> 
	<script language="javascript" type="text/javascript" src="../js/jquery.flot.stack.js"></script>
	<script language="javascript" type="text/javascript" src="../js/table_set.js"></script>
	<script language="javascript" type="text/javascript" src="../js/jquery.boxy.js"></script>

		
		<!-- 1. Add these JavaScript inclusions in the head of your page -->
		<script type="text/javascript" src="../js/js_new/jquery.min.js"></script>
		<script type="text/javascript" src="../js/js_new/highcharts.js"></script>
		
		<style type="text/css" title="currentStyle">
			@import "../js/js_new/demo_page.css";
			@import "../js/js_new/demo_table.css";
		</style>
		
		<!-- 2. Add the JavaScript to initialize the chart on document ready -->
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
		var chart;
		var s0;
		var s1;	
		var x;
		$(document).ready(function() {
			chart = new Highcharts.Chart({
				chart: {
					renderTo: 'container',
					defaultSeriesType: 'column',
					events: {
						load: function() {
								s0 = this.series[0];
								s1 = this.series[1];	
								x = chart.xAxis[0];
								function fetchData_ip_table_data(topip)
							    {	
								    var x = 0;
								    var len = topip.length;
								    for(var i=0;i<10;i++)
								    {
									    x = i+1;
								    	$("#data_show_ip_top tr:eq("+x+") td:eq(1)").html("");//ip地址赋值		
										$("#data_show_ip_top tr:eq("+x+") td:eq(2)").html("");//上行流量		
										$("#data_show_ip_top tr:eq("+x+") td:eq(3)").html("");//下行流量		
										$("#data_show_ip_top tr:eq("+x+") td:eq(4)").html("");//总流量		
										$("#data_show_ip_top tr:eq("+x+") td:eq(5)").html("");
								    }
									for(var i=0;i<len;i++)
									{
									    x = i+1;
										var ip = longtoip(topip[i][3]);
										$("#data_show_ip_top tr:eq("+x+") td:eq(1)").html(ip);//ip地址赋值		
										$("#data_show_ip_top tr:eq("+x+") td:eq(2)").html(Math.round(parseFloat(topip[i][0])/3072*100)/100+"KB");//上行流量		
										$("#data_show_ip_top tr:eq("+x+") td:eq(3)").html(Math.round(parseFloat(topip[i][1])/3072*100)/100+"KB");//下行流量		
										$("#data_show_ip_top tr:eq("+x+") td:eq(4)").html(Math.round(parseFloat(topip[i][2])/3072*100)/100+"KB");//总流量		
										//这里要弹出对话框
										$("#data_show_ip_top tr:eq("+x+") td:eq(5)").html("<a href='#' onclick='Boxy.ask(&quot是否要把该用户放入黑名单&quot, [&quot确定&quot, &quot否&quot], function(r) { alert(r); });'>放入黑名单</a>");
									}													
								}							
								function fetchData_ip()
								{									
								    function onDataReceived(topip)
									{
										var up = [];
										var down = [];
										var ip = [];
										var length = topip.length;
										if(length ==0)
											return;
										for(var i=0;i<length;i++)
										{
											up[i] = Math.round(parseFloat(topip[i][0])/3072*100)/100;
											down[i] = Math.round(parseFloat(topip[i][1])/3072*100)/100;
											ip[i] = longtoip(topip[i][3]);										
										}										
										if(length<10)
										{
										   for(var j = length;j<10;j++)
										   {
										     up[j] = 0;
										     down[j]=0;
										     ip[j] = "0.0.0.0";
										   }
										}
									//	up=[1,2,3,4,5,6,7,8,9,10];
									//	down=[11,12,13,14,15,16,17,18,19,110];
										//ip = ['192.168.1.1', '192.168.1.2', '192.168.1.7', '192.168.1.9', '192.168.1.11','192.168.1.254', '192.168.1.7', '192.168.1.9', '192.168.1.11','192.168.1.254'];
										fetchData_ip_table_data(topip);	
										x.setCategories(ip,true);
										s0.setData(up,false);
										s1.setData(down,false);
										chart.redraw();	
																	
									}
										//		
									    $.ajax({
									            url: "pro_ip_detail_data.php?proid=<?php echo $_REQUEST['proid'];?>",
									            method: 'GET',
									            dataType: 'json',
												cache:false,
									            success: onDataReceived
												});
										   
									}//fectchdata end
								fetchData_ip();	
								// set up the updating of the chart each second
								setInterval(function() {								
									fetchData_ip();										
								}, 3000);
							}
						}
				},
				title: {
					text: ''
				},
				xAxis: {
					categories: ['0.0.0.0', '0.0.0.0', '0.0.0.0', '0.0.0.0', '0.0.0.0','0.0.0.0', '0.0.0.0', '0.0.0.0', '0.0.0.0', '0.0.0.0']
				},
				yAxis: {
					min: 0,
					title: {
						text: '上下行流量（KB）'
					}
				},
				legend: {
					style: {
						left: 'auto',
						bottom: 'auto',
						right: '70px',
						top: '35px'
					},
					backgroundColor: '#FFFFFF',
					borderColor: '#CCC',
					borderWidth: 1,
					shadow: false
				},
				tooltip: {
					formatter: function() {
						return this.series.name +': '+ this.y +'<br/>'+
							 '总流量: '+ Highcharts.numberFormat(this.point.stackTotal,2);
					}
				},
				plotOptions: {
					column: {
						stacking: 'normal'
					}
				},
			   series: [{
					name: '上行流量',
				//	color: '#89A54E',
					data: [0, 0, 0, 0, 0,0, 0, 0, 0, 0]
				}, {
					name: '下行流量',
				//	color:'#AA4643',
					data: [0, 0, 0, 0, 0,0, 0, 0, 0, 0]
				}]
			});
			
			
		});
		</script>
		
	<script type="text/javascript" src="../js/js_new/highslide-full.min.js"></script>
<script type="text/javascript" src="../js/js_new/highslide.config.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="../js/js_new/highslide.css" />

</head> 
<body> 
<h1><?php echo $_REQUEST['name'];?>协议流量最高的10个用户</h1>
<!-- <div id = "ip_top_10_Y">单位:KB</div>
	3. Add the container -->
		<div id="container" style="width: 100%; height: 350px; margin: 0 auto"></div>
<!-- <div id="ip_top_10" class="totalback" style="padding:5px"></div> -->
	<table cellpadding="0" cellspacing="0" border="0"  class="display" id="data_show_ip_top">
	<thead><tr><th align="center">排名号</th><th align="center">IP地址</th><th align="center">上行流量</th><th align="center">下行流量</th><th align="center">总流量</th><th align="center">放入黑名单</th></tr></thead>
	<tbody>
	<tr class="gradeA"><td align="center">1</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center"></td></tr>
	<tr><td align="center">2</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center"></td></tr>
	<tr class="gradeA"><td align="center">3</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center"></td></tr>
	<tr><td align="center">4</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center"></td></tr>
	<tr class="gradeA"><td align="center">5</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center"></td></tr>
	<tr><td align="center">6</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center"></td></tr>
	<tr class="gradeA"><td align="center">7</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center"></td></tr>
	<tr><td align="center">8</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center"></td></tr>
	<tr class="gradeA"><td align="center">9</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center"></td></tr>
	<tr><td align="center">10</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center"></td></tr>
	</tbody>
	<tfoot>
		<tr><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th></tr>
	</tfoot>
	</table>
 </body> 
</html> 