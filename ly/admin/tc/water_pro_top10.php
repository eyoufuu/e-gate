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
<head>
	
		<!-- 1. Add these JavaScript inclusions in the head of your page -->
		<script type="text/javascript" src="../js/js_new/jquery.min.js"></script>
		<script type="text/javascript" src="../js/js_new/highcharts.js"></script>

		<!--[if IE]>
			<script type="text/javascript" src="../js/excanvas.compiled.js"></script>
		<![endif]-->
		
		<style type="text/css" title="currentStyle">
			@import "../js/js_new/demo_page.css";
			@import "../js/js_new/demo_table.css";
		</style>
		<!-- 2. Add the JavaScript to initialize the chart on document ready -->
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
							$("#data_show_pro_top tr:eq("+x+") td:eq(5)").html("<a href='pro_ip_detail.php?proid="+toppro[i][3]+"&name="+proname+"'>详细</a>");
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
									$("#data_show_pro_top tr:eq("+x+") td:eq(5)").html("");
								}
								fetchData_pro_table_data(data_pro);
								//chart.redraw();																
								}//onDataReceived end;
						    	$.ajax({
						            url: "water_pro_top10_d.php",
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
		
					//data: [3.40, 1.05, 2.90, 1.65, 1.35, 2.59, 1.39, 3.07, 2.82]
				}]
				
			});		
		});
		</script>
		
	<script type="text/javascript" src="../js/js_new/highslide-full.min.js"></script>
<script type="text/javascript" src="../js/js_new/highslide.config.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="../js/js_new/highslide.css" />
</head>

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
/*
jQuery(document).ready(function(){
 $(function () {
	var pie_pro_top10 = new JSChart('pro_top_10', 'pie');
    initialize_pie();	
   function initialize_pie()
   {
		var myData = new Array(['0', 1], ['1',1], ['2', 1], ['4', 1], ['5', 1], ['6', 1], ['7', 1], ['8', 1], ['9',1], ['10', 1]);
		var colors = ['#FFCC00', '#FFFF00', '#CCFF00', '#99FF00', '#33FF00', '#00FF66', '#00FF99', '#00FFCC', '#FF0000', '#FF3300'];
		pie_pro_top10.setDataArray(myData);
		pie_pro_top10.setTitle('协议流量前十名');
		pie_pro_top10.colorizePie(colors);
		pie_pro_top10.setPiePosition(300, 160);
		pie_pro_top10.setPieRadius(90);
		pie_pro_top10.setPieUnitsFontSize(8);
		pie_pro_top10.setPieUnitsColor('#474747');
		pie_pro_top10.setPieValuesColor('#474747');
		pie_pro_top10.setPieValuesOffset(-10);
		pie_pro_top10.setTitleColor('#fff');
		pie_pro_top10.setSize(600, 300);
		pie_pro_top10.setBackgroundImage('chart.jpg');
		pie_pro_top10.draw();
   }
    function fetchData_pro_table_data(toppro)
	{
		for(var i=0;i<toppro.length;i++)
		{
		    x = i+1;
			$("#data_show_pro_top tr:eq("+x+") td:eq(1)").html(toppro[i][3]);//赋值		
			$("#data_show_pro_top tr:eq("+x+") td:eq(2)").html(toppro[i][0]+"KB");//上行流量		
			$("#data_show_pro_top tr:eq("+x+") td:eq(3)").html(toppro[i][1]+"KB");//下行流量		
			$("#data_show_pro_top tr:eq("+x+") td:eq(4)").html(toppro[i][2]+"KB");//总流量		
			$("#data_show_pro_top tr:eq("+x+") td:eq(5)").html("<a href='ip_flow_detail.php?ip='"+toppro[i][3]+ ">详细</a>");
		}
	}	
	
   function fetchData_ip()
   {
    function onDataReceived(data_pro)
	{
	    //alert(data_ip_pro.ip);
		var piedata=[];
		for(var i=0;i<data_pro.length;i++)
		{
		    data_pro[i][0] = Math.floor(parseFloat(data_pro[i][0])/1024*1000)/1000;
		    data_pro[i][1] = Math.floor(parseFloat(data_pro[i][1])/1024*1000)/1000;
		    data_pro[i][2] = Math.floor(parseFloat(data_pro[i][2])/1024*1000)/1000;
			data_pro[i][3] = g_id_name[data_pro[i][3]];
			piedata.push([data_pro[i][3],data_pro[i][2]]);
		}
		if(data_pro.length>0)
		{
			pie_pro_top10.setDataArray(piedata);
			pie_pro_top10.draw();
			fetchData_pro_table_data(data_pro);	
		}
	}//onDataReceived end;
    $.ajax({
            url: "water_pro_top10_d.php",
            method: 'GET',
            dataType: 'json',
			cache:false,
            success: onDataReceived
			});
		   setTimeout(fetchData_ip, 3000);
	}//fectchdata end
	
     setTimeout(fetchData_ip, 2000);
		 
	/////////////////////////////////
	});
	
	
	
});
*/
</script>
</head> 
<body> 
<h1>3秒统计 网络出口最多的10个协议流量分布</h1>
 <!--    <div id = "pro_top_10_Y" >单位:KB</div>
   <div id="pro_top_10" style="padding:5px" align="center"></div>-->  
  		<!-- 3. Add the container -->
		<div id="container" style="width: 100%; height: 350px; margin: 0 auto"></div>
  	<table cellpadding="0" cellspacing="0" border="0"  class="display" id="data_show_pro_top">
	<thead><tr><th align="center">排名号</th><th align="center">协议名称</th><th align="center">上行流量</th><th align="center">下行流量</th><th align="center">总流量</th><th align="center">详细</th></tr></thead>
	<tbody>
	<tr class="gradeA"><td align="center">1</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td></tr>
	<tr><td align="center">2</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td></tr>
	<tr class="gradeA"><td align="center">3</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td></tr>
	<tr><td align="center">4</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td></tr>
	<tr class="gradeA"><td align="center">5</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td></tr>
	<tr><td align="center">6</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td></tr>
	<tr class="gradeA"><td align="center">7</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td></tr>
	<tr><td align="center">8</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td></tr>
	<tr class="gradeA"><td align="center">9</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td></tr>
	<tr><td align="center">10</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td></tr>
	</tbody>
	<tfoot><tr><th align="center">&nbsp;</th><th align="center">&nbsp;</th><th align="center">&nbsp;</th><th align="center">&nbsp;</th><th align="center">&nbsp;</th><th align="center">&nbsp;</th></tr></tfoot>
	</table>
 <script language="javascript">
//senfe("data_show_pro_top","#f8fbfc","#e5f1f4","#ecfbd4","#bce774");
</script>
 </body> 
</html> 