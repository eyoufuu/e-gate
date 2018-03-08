<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html> 
 <head> 
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
    <title>实时流量分析</title> 
	<link href="../common/main.css" rel="stylesheet" type="text/css"/>
	<script language="javascript"  type="text/javascript" src="../common/common.js"></script>
    <!--[if IE]><script language="javascript" type="text/javascript" src="../js/excanvas.min.js"></script><![endif]--> 
	<script  language="javascript" type="text/javascript" src="../js/jquery.js"></script>
    <script  language="javascript" type="text/javascript" src="../js/jquery.flot.js"></script> 
	<script  language="javascript"  type="text/javascript" src="../js/jquery.flot.stack.js"></script>
	<script  language="javascript" type="text/javascript" src="../js/table_set.js"></script>
	
	
		<!-- 1. Add these JavaScript inclusions in the head of your page -->
		<script type="text/javascript" src="../js/js_new/jquery.min.js"></script>
		<script type="text/javascript" src="../js/js_new/highcharts.js"></script>

	<style type="text/css" title="currentStyle">
			@import "../js/js_new/demo_page.css";
			@import "../js/js_new/demo_table.css";
		</style>
		
		<!--[if IE]>
			<script type="text/javascript" src="../js/js_new/excanvas.compiled.js"></script>
		<![endif]-->
		
		
		<!-- 2. Add the JavaScript to initialize the chart on document ready -->
		<script type="text/javascript">
		var chart;
		var dataup =[];
		var datadown=[];
		var datatotal=[];

		var flag = 0;
		var dataup_sum_KB = 0;
		var datadown_sum_KB = 0;
		var datatotal_sum_KB = 0;
		var dataup_sum_M = 0;
		var datadown_sum_M = 0;
		var datatotal_sum_M = 0;
		
		var obj_total;
		var obj_down ;
		var obj_up;

		$(document).ready(function() {
			chart = new Highcharts.Chart({
				chart: {
					renderTo: 'container',
					defaultSeriesType: 'spline',
					margin: [50, 100, 60, 80],
					events: {
						load: function() {			
							// set up the updating of the chart each second
				obj_total = this.series[0];
				 obj_down = this.series[1];
				 obj_up = this.series[2];
				 	function fetchData_total_data(up,down,total)
				    {
				        dataup_sum_KB    += up;
				        dataup_sum_KB = Math.round(dataup_sum_KB*100)/100;		
				    	dataup_sum_M = dataup_sum_KB/1024;
				        dataup_sum_M = Math.round(dataup_sum_M*100)/100;

						datadown_sum_KB  += down;
						datadown_sum_KB = Math.round(datadown_sum_KB*100)/100;
						datadown_sum_M = datadown_sum_KB/1024;
						datadown_sum_M = Math.round(datadown_sum_M*100)/100;

						datatotal_sum_KB = dataup_sum_KB+datadown_sum_KB;
						datatotal_sum_KB = Math.round(datatotal_sum_KB*100)/100;
						datatotal_sum_M = datatotal_sum_KB/1024;
				        datatotal_sum_M = Math.round(datatotal_sum_M*100)/100;
						$("#data_show_total tr:eq("+1+") td:eq(0)").html("第"+(flag++)+"次");//上行流量		
						$("#data_show_total tr:eq("+1+") td:eq(1)").html(up+"KB");//上行流量		
						$("#data_show_total tr:eq("+1+") td:eq(2)").html(down+"KB");//下行流量		
						$("#data_show_total tr:eq("+1+") td:eq(3)").html(total+"KB");//总流量		
						$("#data_show_total tr:eq("+1+") td:eq(4)").html("<a href='water_ip_top10.php'>详细</a>");
						$("#data_show_total tr:eq("+1+") td:eq(5)").html("黑名单");
						if(dataup_sum_KB <1024)
						{
							$("#data_show_total tr:eq("+2+") td:eq(1)").html(dataup_sum_KB+"KB");
							$("#data_show_total tr:eq("+2+") td:eq(2)").html(datadown_sum_KB+"KB");
							$("#data_show_total tr:eq("+2+") td:eq(3)").html(datatotal_sum_KB+"KB");
						}
						else
						{
							$("#data_show_total tr:eq("+2+") td:eq(1)").html(dataup_sum_M+"M");
							$("#data_show_total tr:eq("+2+") td:eq(2)").html(datadown_sum_M+"M");
							$("#data_show_total tr:eq("+2+") td:eq(3)").html(datatotal_sum_M+"M");
						}
					}
							function fetchdata()
							{
								 function onDataReceived(series) {
										
											var up = parseFloat(series[0].up)/3072;
											up = Math.round(up*100)/100;
											var down = parseFloat(series[0].down)/3072;
											down = Math.round(down*100)/100;
											var total = up+down;
											total = Math.round(total*100)/100;
											var date = new Date();
											var	time = date.getTime()+8*3600000;
											
											obj_total.addPoint([time,total],false,true);
											obj_down.addPoint([time,down],false,true);
											obj_up.addPoint([time,up],false,true);
											chart.redraw();
											fetchData_total_data(up,down,total);
									}
								 $.ajax({
						                url: "water_total_d.php",
										cache:false,
						                method: 'POST',
						                dataType: 'json',
						                success: onDataReceived
						        	});	  
							}		
						//	fetchdata();	
							setInterval(function() {
								fetchdata();								
							}, 3000);
						}
					}
				},
				title: {
					text: '',
					style: {
						margin: '10px 100px 0 0' // center it
					}
				},
				xAxis: {
					type: 'datetime',
					tickPixelInterval: 100
				},
				yAxis: {
					title: {
						text: '流量（KB）'
					},
					plotLines: [{
						value: 0,
						width: 1,
						color: '#808080'
					}]
				},
				tooltip: {
					formatter: function() {
			                return '<b>'+ this.series.name +'</b><br/>'+
							Highcharts.dateFormat('%H:%M:%S', this.x) +'<br/>'+ 
							Highcharts.numberFormat(this.y, 2);
					}
				},
				legend: {
					layout: 'vertical',
					style: {
						left: 'auto',
						bottom: 'auto',
						right: '1px',
						top: '50px'
					}
				},
				series: [{
					name: '总流量',
					data: (function() {
						// generate an array of random data
						var data = [],
							time = (new Date()).getTime()+8*3600000,
							i;
						for (i = -19; i <= 0; i++) {
							data.push({
								x: time + i * 1000,
								y: 0
							});
						}
						return data;
					})()
				},							
					{
					name: '下行流量',
					data: (function() {
						// generate an array of random data
						var data = [],
							time = (new Date()).getTime()+8*3600000,
							i;
						for (i = -19; i <= 0; i++) {
							data.push({
								x: time + i * 1000,
								y: 0
							});
						}
						return data;
					})()
				},
				{
					name: '上行流量',
					data: (function() {
						// generate an array of random data
						var data = [],
								time = (new Date()).getTime()+8*3600000,
							i;
						for (i = -19; i <= 0; i++) {
							data.push({
								x: time + i * 1000,
								y: 0
							});
						}
						return data;
					})()
				}
				]
			});			
		});
		</script>
		
	<script type="text/javascript" src="../js/js_new/highslide-full.min.js"></script>
<script type="text/javascript" src="../js/js_new/highslide.config.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="../js/js_new/highslide.css" />

<script type="text/javascript">
/*
var PLOT;
jQuery(document).ready(function(){
$(function () {
    var options = 
		{
        lines: { show: true },
        points: { show: true },
        //xaxis: { tickDecimals: 0 }
		 xaxis: {
           // ticks: [[0,'x'], [1, "x"], [2, "x"], [3, "x"], [4, "x"]]
        }
		};
	var flag = 29;
		
    var alreadyFetched = {};
    PLOT  = $.plot($("#placeholder"), [[0,0],[1,0],[2,0],[3,0],[4,0],[5,0],[6,0],[7,0],[8,0],[9,0]], options);
	var dataup =[];
	var datadown=[];
	var datatotal=[];
	var dataup_sum_KB = 0;
	var datadown_sum_KB = 0;
	var datatotal_sum_KB = 0;
	var dataup_sum_M = 0;
	var datadown_sum_M = 0;
	var datatotal_sum_M = 0;
	function fetchData_total_data(up,down,total)
    {
        dataup_sum_KB    += up;
        dataup_sum_KB = Math.round(dataup_sum_KB*1000)/1000;		
    	dataup_sum_M = dataup_sum_KB/1024;
        dataup_sum_M = Math.round(dataup_sum_M*1000)/1000;

		datadown_sum_KB  += down;
		datadown_sum_KB = Math.round(datadown_sum_KB*1000)/1000;
		datadown_sum_M = datadown_sum_KB/1024;
		datadown_sum_M = Math.round(datadown_sum_M*1000)/1000;

		datatotal_sum_KB = dataup_sum_KB+datadown_sum_KB;
		datatotal_sum_KB = Math.round(datatotal_sum_KB*1000)/1000;
		datatotal_sum_M = datatotal_sum_KB/1024;
        datatotal_sum_M = Math.round(datatotal_sum_M*1000)/1000;
		$("#data_show_total tr:eq("+1+") td:eq(0)").html("第"+(flag-28)+"次");//上行流量		
		$("#data_show_total tr:eq("+1+") td:eq(1)").html(up+"KB");//上行流量		
		$("#data_show_total tr:eq("+1+") td:eq(2)").html(down+"KB");//下行流量		
		$("#data_show_total tr:eq("+1+") td:eq(3)").html(total+"KB");//总流量		
		$("#data_show_total tr:eq("+1+") td:eq(4)").html("<a href='ip_flow_detail.php'>详细</a>");
		$("#data_show_total tr:eq("+1+") td:eq(5)").html("黑名单");
		if(dataup_sum_KB <1024)
		{
			$("#data_show_total tr:eq("+2+") td:eq(1)").html(dataup_sum_KB+"KB");
			$("#data_show_total tr:eq("+2+") td:eq(2)").html(datadown_sum_KB+"KB");
			$("#data_show_total tr:eq("+2+") td:eq(3)").html(datatotal_sum_KB+"KB");
		}
		else
		{
			$("#data_show_total tr:eq("+2+") td:eq(1)").html(dataup_sum_M+"M");
			$("#data_show_total tr:eq("+2+") td:eq(2)").html(datadown_sum_M+"M");
			$("#data_show_total tr:eq("+2+") td:eq(3)").html(datatotal_sum_M+"M");
		}
		
	}

	for(var i = 0;i<30;i++)
	{
	   dataup.push([i,0]);
	   datadown.push([i,0]);
	   datatotal.push([i,0]);
	}

    function fetchData() {

        function onDataReceived(series) {
		dataup.shift();
		datadown.shift();
		datatotal.shift();
		var up = parseFloat(series[0].up)/1024;
		up = Math.round(up*1000)/1000;
		var down = parseFloat(series[0].down)/1024;
		down = Math.round(down*1000)/1000;
		var total = up+down;
		total = Math.round(total*1000)/1000;
		dataup.push([flag,up]);
		datadown.push([flag,down]);
		datatotal.push([flag,total]);
		flag++;
	
        var data_all = [{label: "总流量", data:datatotal},{label:"上行流量",data:dataup},{label:"下行流量",data:datadown}];
		//
		PLOT.setData(data_all);
        PLOT.setupGrid();
        PLOT.draw();
		data_all = null;
		//CollectGarbage();
        //var plot = $.plot($("#placeholder"), data_all, options);
		fetchData_total_data(up,down,total);
         
        }
        
        $.ajax({
                url: "water_total_d.php",
				cache:false,
                method: 'POST',
                dataType: 'json',
                success: onDataReceived
        });
        setTimeout(fetchData, 4000);

    }//fetchData end
    setTimeout(fetchData, 1000);
	
});
});*/
</script>
<script>
var chart_onehour;
$(document).ready(function() {
	chart_onehour = new Highcharts.Chart({
		chart: {
			renderTo: 'container_onehour',
			defaultSeriesType: 'column',
			events: {
				load: function() {
							var s1 = this.series[0];
							var s2 = this.series[1];
							function onDataReceived(topip)
							{
								//var topip= data_ip_pro;
								var d1 =[];
								var d2 =[];		
								for(var i=0;i<topip.length;i++)
								{
								    var up = Math.round(parseFloat(topip[i][1])/1024/1024*1000)/1000;
									var down = Math.round(parseFloat(topip[i][2])/1024/1024*1000)/1000;
									d1[i] = up;
									d2[i] = down;
								}
								if(topip.length<6)
								{
								   for(var j = topip.length;j<6;j++)
								   {
								     d1[j] = 0;
								     d2[j] = 0;
								   }
								}
								s1.setData(d1,true);
								s2.setData(d2,true);
							//	chart.redraw();						
						       
							}//onDataReceived end;						         
						   $.ajax({
						            url: "water_total_onehour_d.php",
						            method: 'GET',
						            dataType: 'json',
									cache:false,
						            success: onDataReceived
									});
						}
					}
		},
		title: {
			text: ''
		},
		xAxis: {
			categories: ['0-10分钟','10-20分钟', '20-30分钟','30-40分钟','40-50分钟','50-60分钟']
		},
		yAxis: {
			min: 0,
			title: {
				text: '流量（MB）'
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
				return '<b>'+ this.x +'</b><br/>'+
					 this.series.name +': '+ this.y +'<br/>'+
					 '总流量: '+ Highcharts.numberFormat(this.point.stackTotal,3);
			}
		},
		plotOptions: {
			column: {
				stacking: 'normal',
					pointWidth: 40
			}
		},
	        series: [{
			name: '上行流量',
		//	color: '#89A54E',
			data: [6, 5, 4, 3,2,1]
		}, {
			name: '下行流量',
		//	color:'#AA4643',
			data: [2, 2, 2, 2, 2,2]
		}]
	});
});

/*jQuery(document).ready(function(){
	var d1 = [[0,0],[1,0],[2,0],[3,0],[4,0],[5,0]];
	var d2 = [[0,0],[1,0],[2,0],[3,0],[4,0],[5,0]];
        $.plot($("#total_onehour"), [ {label: "上行流量",data:d1,color:"rgb(0, 255, 0)"}, {label: "下行流量",data:d2,color:"rgb(255, 0, 0)"} ], 
		{
            series: 
			{
			   stack: true,bars: { show: true, barWidth: 0.3 }
		    },
			xaxis: 
			{
                ticks: [[0,"0-10分钟"], [1, "10-20分钟"], [2, "20-30分钟"], [3, "30-40分钟"], [4, "40-50分钟"],[5,"50-60分钟"]]
			}			
		});
		
    function onDataReceived(topip)
	{
		//var topip= data_ip_pro;
		d1 =[];
		d2 =[];		
		for(var i=0;i<topip.length;i++)
		{
		    var up = Math.round(parseFloat(topip[i][1])/1024/1024*1000)/1000;
			var down = Math.round(parseFloat(topip[i][2])/1024/1024*1000)/1000;
			d1.push([topip[i][0],up]);
			d2.push([topip[i][0],down]);
		}
	
        $.plot($("#total_onehour"), [ {label: "上行流量",data:d1,color:"rgb(0, 255, 0)"}, {label: "下行流量",data:d2,color:"rgb(255, 0, 0)"} ], {
            series: 
			{
			  stack: true,bars: { show: true, barWidth: 0.3 }
			},
			xaxis: 
			{
                ticks: [[0,"0-10分钟"], [1, "10-20分钟"], [2, "20-30分钟"], [3, "30-40分钟"], [4, "40-50分钟"],[5,"50-60分钟"]]
			}			
			
		});
	}//onDataReceived end;
         
   $.ajax({
            url: "water_total_onehour_d.php",
            method: 'GET',
            dataType: 'json',
			cache:false,
            success: onDataReceived
			});
});*/
</script>
<script>
var chart_24hour;
$(document).ready(function() {
	chart_onehour = new Highcharts.Chart({
		chart: {
			renderTo: 'container_24hour',
			defaultSeriesType: 'column',
			events: {
				load: function() {
						var s1 = this.series[0];
						var s2 = this.series[1];
						function onDataReceived(topip)
						{
							d1=[];
							d2=[];		
							for(var i=0;i<topip.length;i++)
							{
							    var up   =  parseFloat(topip[i][1])/1024/1024;
								var down =  parseFloat(topip[i][2])/1024/1024;
								
								up   = Math.round(up*1000)/1000;
								down = Math.round(down*1000)/1000;
								d1[i] = up;
								d2[i] = down;
							}
							s1.setData(d1,true);
							s2.setData(d2,true);
					//		chart.redraw();				       
						}//onDataReceived end;				         
						   $.ajax({
						            url: "water_total_24h_d.php",
						            method: 'GET',
						            dataType: 'json',
									cache:false,
						            success: onDataReceived
									});
							}
					}
		},
		title: {
			text: ''
		},
		xAxis: {
			categories: ['0-2小时', '2-4小时', '4-6小时', '6-8小时','8-10小时','10-12小时','12-14小时','14-16小时','16-18小时','18-20小时','20-22小时','22-24小时']
		},
		yAxis: {
			min: 0,
			title: {
				text: '流量（MB）'
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
				return '<b>'+ this.x +'</b><br/>'+
					 this.series.name +': '+ this.y +'<br/>'+
					 '总流量: '+ Highcharts.numberFormat(this.point.stackTotal,3);
			}
		},
		plotOptions: {
			column: {
				stacking: 'normal',
				pointWidth: 30
			}
		},
	        series: [{
			name: '上行流量',
		//	color: '#89A54E',
			data: [6, 5, 4, 3,2,1,6, 5, 4, 3,2,1]
		}, {
			name: '下行流量',
		//	color:'#AA4643',
			data: [2, 2, 2, 2, 2,2,2, 2, 2, 2, 2,2]
		}]
	});
});
/*
jQuery(document).ready(function(){	
	var d1 = [[0,0],[1,0],[2,0],[3,0],[4,0],[5,0],[7,0],[8,0],[9,0]];
	var d2 = [[0,0],[1,0],[2,0],[3,0],[4,0],[5,0],[7,0],[8,0],[9,0]];        		
    $.plot($("#total_today"), [ {label: "上行流量",data:d1,color:"rgb(0, 255, 0)"}, {label: "下行流量",data:d2,color:"rgb(255, 0, 0)"} ], {
            series: {stack: true,bars: { show: true, barWidth: 0.3 }},
			xaxis: 
			{
                ticks: [[0,"0-1小时"], [1, "1-2小时"], [2, "2-3小时"], [3, "3-4小时"], [4, "4-5小时"],[5,"5-6小时"],[6,"6-7小时"],[7,"7-8小时"],[8,"8-9小时"],[9,"9-10小时"],[10,"10-11小时"],[11,"11-12小时"],[12,"12-13小时"],[13,"13-14小时"],[14,"14-15小时"],[15,"15-16小时"],[16,"16-17小时"],[17,"17-18小时"],[18,"18-19小时"],[19,"19-20小时"],[20,"20-21小时"],[21,"21-22小时"],[22,"22-23小时"],[23,"23-24小时"]]
			}			
			});
   function onDataReceived(topip)
	{
		//var topip= data_ip_pro;


		d1=[];
		d2=[];		
		for(var i=0;i<topip.length;i++)
		{
		    var up   =  parseFloat(topip[i][1])/1024/1024;
			var down =  parseFloat(topip[i][2])/1024/1024;
			
			up   = Math.round(up*1000)/1000;
			down = Math.round(down*1000)/1000;
			d1.push([topip[i][0],up]);
			d2.push([topip[i][0],down]);
		}
	
        $.plot($("#total_today"), [ {label: "上行流量",data:d1,color:"rgb(0, 255, 0)"}, {label: "下行流量",data:d2,color:"rgb(255, 0, 0)"} ], {
            series: {stack: true,bars: { show: true, barWidth: 0.3 }},
			xaxis: 
			{
                ticks: [[0,"0-2小时"], [1, "2-4小时"], [2, "4-6小时"], [3, "6-8小时"], [4, "8-10小时"],[5,"10-12小时"],[6,"12-14小时"],[7,"14-16小时"],[8,"16-18小时"],[9,"18-20小时"],[10,"20-22小时"],[11,"22-24小时"]]
			}			
			});
	}//onDataReceived end;
         
   $.ajax({
            url: "water_total_24h_d.php",
            method: 'GET',
            dataType: 'json',
			cache:false,
            success: onDataReceived
			});
});*/
</script>
</head> 
<body> 
<h1>实时流量查看表 3秒中统计值--网络上行,下行,总量表</h1>	
  <!--    <div id = "total_data_Y">单位:KB<div>
    <div id="placeholder" class = "totalback"></div>-->
 <!-- 3. Add the container -->
	<div id="container" style="width: 100%; height: 350px; margin: 0 auto"></div>
 
<!-- <table class="warp_table" id="data_show_total"> -->	
	<table cellpadding="0" cellspacing="0" border="0"  class="display" id="data_show_total">	
	<thead><tr><th align="center">收到次数</th><th align="center">上行流量</th><th align="center">下行流量</th><th align="center">总流量</th><th align="center">详细</th></tr></thead>
	<tbody><tr class="gradeA"><td align="center">第0次</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td></tr>
	<tr><td align="center">收到累计</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">--</td></tr>
	</tbody>
	<tfoot>
		<tr><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th></tr>
	</tfoot>	
	</table>
<h1>最近一小时内流量</h1>
 <!--   <div id = "total_onehour_Y">单位:M字节<div>
    <div id="total_onehour" class="totalback"></div>-->	
 <!-- 3. Add the container -->
	<div id="container_onehour" style="width: 100%; height: 300px; margin: 0 auto"></div>
 
<h1>今日流量(24小时)</h1>
  <!--  <div id = "total_today_Y">单位:M字节<div>
   <div id= "total_today" class = "totalback"></div> -->
   	<!-- 3. Add the container -->
	<div id="container_24hour" style="width: 100%; height: 300px; margin: 0 auto"></div>
<script id="source" language="javascript" type="text/javascript"> 
$(function () {

    $("input.dataUpdate").click(function () {
  
    });
});
</script> 
 <script language="javascript">
///senfe("data_show_total","#f8fbfc","#e5f1f4","#ecfbd4","#bce774");
/* $(document).ready(function() {
		$('#data_show_total').dataTable({
			"bPaginate": false,
			"bSort": false,
			"bInfo": false,
			"bAutoWidth": false,
			"bFilter": false,
			"bLengthChange": false,
		});
	} );*/
</script>
</body> 
</html> 