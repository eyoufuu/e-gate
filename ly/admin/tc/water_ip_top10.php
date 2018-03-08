<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<html> 
 <head> 
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
    <title>实时流量分析-网络IP流量前十名</title> 
	<link href="../common/main.css" rel="stylesheet" type="text/css"/>

	<link rel="stylesheet" href="../js/jquerybox/css/boxy.css" type="text/css" />

	<script language="javascript"  type="text/javascript" src="../common/common.js"></script>
    <!--[if IE]><script language="javascript" type="text/javascript" src="../js/excanvas.min.js"></script><![endif]--> 
	<script language="javascript" type="text/javascript" src="../js/jquery.js" ></script>
    <script language="javascript" type="text/javascript" src="../js/jquery.flot.js"></script> 
	<script language="javascript" type="text/javascript" src="../js/jquery.flot.stack.js"></script>
	<script language="javascript" type="text/javascript" src="../js/table_set.js"></script>

	
		
		<!-- 1. Add these JavaScript inclusions in the head of your page -->
		<script type="text/javascript" src="../js/js_new/jquery.min.js"></script>
		<script type="text/javascript" src="../js/js_new/highcharts.js"></script>
		
		<style type="text/css" title="currentStyle">
			@import "../js/js_new/demo_page.css";
			@import "../js/js_new/demo_table.css";
		</style>

		<!--[if IE]>
			<script type="text/javascript" src="../js/excanvas.compiled.js"></script>
		<![endif]-->

		
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
		function intoblacklist(ip)
		{
			var str = longtoip(ip);
			if(confirm("确定把"+str+"放入黑名单吗？"))
		    {//如果是true
				 $.ajax({
			            url: "add_ip_black.php?ip="+str,
			            method: 'POST',
			            dataType: 'json',
						cache:false
						});
		    }
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
										$("#data_show_ip_top tr:eq("+x+") td:eq(6)").html("");
								    }
									for(var i=0;i<len;i++)
									{
									    x = i+1;
										var ip = longtoip(topip[i][3]);
										$("#data_show_ip_top tr:eq("+x+") td:eq(1)").html(ip);//ip地址赋值		
										$("#data_show_ip_top tr:eq("+x+") td:eq(2)").html(Math.round(parseFloat(topip[i][0])/3072*100)/100+"KB");//上行流量		
										$("#data_show_ip_top tr:eq("+x+") td:eq(3)").html(Math.round(parseFloat(topip[i][1])/3072*100)/100+"KB");//下行流量		
										$("#data_show_ip_top tr:eq("+x+") td:eq(4)").html(Math.round(parseFloat(topip[i][2])/3072*100)/100+"KB");//总流量		
										$("#data_show_ip_top tr:eq("+x+") td:eq(5)").html("<a href='ip_pro_detail.php?ip="+topip[i][3]+ "'>详细</a>");
										//这里要弹出对话框
										//$("#data_show_ip_top tr:eq("+x+") td:eq(6)").html("<a href='#' onclick='Boxy.ask(&quot是否要把该用户放入黑名单&quot, [&quot确定&quot, &quot否&quot], function(r) {alert(r);},{title: &quot提示&quot});'>放入黑名单</a>");
										$("#data_show_ip_top tr:eq("+x+") td:eq(6)").html("<a href='#' onclick='intoblacklist("+topip[i][3]+")'>放入黑名单</a>");
									}													
									//
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
									            url: "water_ip_top10_d.php",
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




<script type="text/javascript">
/*
var PLOT;
jQuery(document).ready(function(){
    function longtoip(ip)
	{
	   var s1 = ip&0xff;
	   var s2 = (ip&0xffff)>>8;
	   var s3 = (ip>>16)&0xff;
	   var s4 = ip>>>24;
	   var sip = s1+"."+s2+"."+s3+"."+s4;
	   return sip;
	}
	$(function () {
	 Boxy.DEFAULTS.title = "标题";
   	function fetchData_ip_table_data(topip)
    {	
	    var x = 0;
		for(var i=0;i<topip.length;i++)
		{
		    x = i+1;
			var ip = topip[i][3];
			$("#data_show_ip_top tr:eq("+x+") td:eq(1)").html(ip);//ip地址赋值		
			$("#data_show_ip_top tr:eq("+x+") td:eq(2)").html(topip[i][0]+"KB");//上行流量		
			$("#data_show_ip_top tr:eq("+x+") td:eq(3)").html(topip[i][1]+"KB");//下行流量		
			$("#data_show_ip_top tr:eq("+x+") td:eq(4)").html(topip[i][2]+"KB");//总流量		
			$("#data_show_ip_top tr:eq("+x+") td:eq(5)").html("<a href='ip_flow_detail.php?ip="+ip+ ">详细</a>");
			//这里要弹出对话框
			$("#data_show_ip_top tr:eq("+x+") td:eq(6)").html("<a href='#' onclick='Boxy.ask(&quot是否要把该用户放入黑名单&quot, [&quot确定&quot, &quot否&quot], function(r) { alert(r); });'>放入黑名单</a>");
		}													
	}
    var options = 
	{    
		   series: {
                stack: true,
                bars: { show: true, barWidth: 0.3 }
            },
			xaxis: 
			{
                //ticks: [[0,topip[0][3]], [1, topip[1][3]], [2, topip[2][3]], [3, topip[3][3]], [4, topip[4][3]],[5,topip[5][3]],[6,topip[6][3]],[7,topip[7][3]],[8,topip[8][3]],[9,topip[9][3]]]
			}			
	};
	PLOT = $.plot($("#ip_top_10"), [ {label: "上行流量",data:[],color:"rgb(10, 255, 5)"}, {label: "下行流量",data:[],color:"rgb(255, 0, 0)"} ],options); 
	
   function fetchData_ip()
   {
    function onDataReceived(topip)
	{
		//var topip= data_ip_pro;
		var d1 = [];
		var d2 = [];
		for(var i=0;i<topip.length;i++)
		{
		    topip[i][0] = Math.round(parseFloat(topip[i][0])/1024*1000)/1000;
			topip[i][1] = Math.round(parseFloat(topip[i][1])/1024*1000)/1000;
			topip[i][2] = Math.round((topip[i][0] + topip[i][1])*1000)/1000;
			topip[i][3] = longtoip(topip[i][3]);
			d1.push([i,topip[i][0]]);
			d2.push([i,topip[i][1]]);
		}
		var length = topip.length;
		if(topip.length<10)
		{
		   for(var j = length;j<10;j++)
		   {
		     var t1 = new Array();
			 t1[0] = 0;t1[1]=0;t1[2]=0;t1[3]=0;
			 topip[j]= t1;
			 d1.push([j,0]);
			 d2.push([j,0]);
		   }
		}
		if(topip.length>0)
		{
		
        var data_all = [ {label: "上行流量",data:d1,color:"rgb(10, 255, 5)"}, {label: "下行流量",data:d2,color:"rgb(255, 0, 0)"}];
		var options_1 = 
		{    
		   series: {
                stack: true,
                bars: { show: true, barWidth: 0.3 }
            },
			xaxis: 
			{
                ticks: [[0,topip[0][3]], [1, topip[1][3]], [2, topip[2][3]], [3, topip[3][3]], [4, topip[4][3]],[5,topip[5][3]],[6,topip[6][3]],[7,topip[7][3]],[8,topip[8][3]],[9,topip[9][3]]]
			}			
		};

        PLOT.setData(data_all);
        PLOT.setupGrid();
        PLOT.draw();
		
		}
		fetchData_ip_table_data(topip);	
	}//onDataReceived end;
    $.ajax({
            url: "water_ip_top10_d.php",
            method: 'GET',
            dataType: 'json',
			cache:false,
            success: onDataReceived
			});
		   setTimeout(fetchData_ip, 3000);
	}//fectchdata end
	
     setTimeout(fetchData_ip, 1000);
	/////////////////////////////////
	});
});*/
</script>
</head> 
<body> 
<h1>流量最高的10个用户(每3秒)</h1>
<!-- <div id = "ip_top_10_Y">单位:KB</div>
	3. Add the container -->
		<div id="container" style="width: 100%; height: 350px; margin: 0 auto"></div>
<!-- <div id="ip_top_10" class="totalback" style="padding:5px"></div> -->
	<table cellpadding="0" cellspacing="0" border="0"  class="display" id="data_show_ip_top">
	<thead><tr><th align="center">排名号</th><th align="center">IP地址</th><th align="center">上行流量</th><th align="center">下行流量</th><th align="center">总流量</th><th align="center">详细</th><th align="center">放入黑名单</th></tr></thead>
	<tbody>
	<tr class="gradeA"><td align="center">1</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center"></td></tr>
	<tr><td align="center">2</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center"></td></tr>
	<tr class="gradeA"><td align="center">3</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center"></td></tr>
	<tr><td align="center">4</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center"></td></tr>
	<tr class="gradeA"><td align="center">5</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center"></td></tr>
	<tr><td align="center">6</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center"></td></tr>
	<tr class="gradeA"><td align="center">7</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center"></td></tr>
	<tr><td align="center">8</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center"></td></tr>
	<tr class="gradeA"><td align="center">9</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center"></td></tr>
	<tr><td align="center">10</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center"></td></tr>
	</tbody>
	<tfoot>
		<tr><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th></tr>
	</tfoot>
	</table>
<!-- <h1>黑名单列表</h1> -->
 <script language="javascript">
//senfe("data_show_ip_top","#f8fbfc","#e5f1f4","#ecfbd4","#bce774");
</script>

      	<script type="text/javascript">
	/*				var diagnose = function(boxy) {
						alert("位置: " + boxy.getPosition() +
							  "\n大小: " + boxy.getSize() +
							  "\n内容区域大小: " + boxy.getContentSize() +
							  "\n中心点: " + boxy.getCenter());
					};
					$(function() {
					  Boxy.DEFAULTS.title = "标题";
					  // Diagnostics
					  $("#diagnostics").click(function() {
						  new Boxy("<div><a href='#nogo' onclick='diagnose(Boxy.get(this));'>显示诊断信息</a></div>");
						  return false;
					  });
					  
					  // Set content
					  var setContent = null;
					  $("#set-content-open").click(function() {
						  setContent = new Boxy(
							"<div style='background-color:red'>这里是显示的内容</div>", {
							  behaviours: function(c) {
								c.hover(function() {
								  $(this).css('backgroundColor', 'green');
								}, function() {
								  $(this).css('backgroundColor', 'pink');
								});
							  }
							}
						  );
						  return false;
					  });
					  $("#set-content").click(function() {
						  setContent.setContent("<div style='background-color:blue'>这里是新添加的显示内容</div>");
						  return false;
					  });
					  
					  // Callbacks
					  $("#after-hide").click(function() {
						  new Boxy("<div>测试内容</div>", {
							afterHide: function() {
							  alert("隐藏后回调");
							}
						  });
						  return false;
					  });	  
					  $("#before-unload").click(function() {
						  new Boxy("<div>测试内容</div>", {
							beforeUnload: function() {
							  alert("卸载前调用");
							},
							unloadOnHide: true
						  });
						  return false;
					  });  
					  $("#before-unload-no-auto-unload").click(function() {
						  new Boxy("<div>测试内容</div>", {
							beforeUnload: function() {
							  alert("这个不应该看见的");
							},
							unloadOnHide: false
						  });
						  return false;
					  });		  
					  $("#after-drop").click(function() {
						  new Boxy("<div>测试内容</div>", {
							afterDrop: function() {
							  alert("放下后: " + this.getPosition());
							},
							draggable: true
						  });
						  return false;
					  });	  
					  $("#after-show").click(function() {
						  new Boxy("<div>测试内容</div>", {
							afterShow: function() {
							  alert("显示后: " + this.getPosition());
							}
						  });
						  return false;
					  });
					  
					  // Z-index
					  var zIndex = null;
					  $("#z-index").click(function() {
						  zIndex = new Boxy(
							"<div>测试内容</div>", { clickToFront: true }
						  );
						  return false;
					  });	  
					  $("#z-index-latest").click(function() {
						  zIndex.toTop();
						  return false;
					  });
					  
					  // Modals
					  function newModal() {
						  new Boxy("<div><a href='#'>打开一个堆叠的模态</a> | <a href='#' onclick='alert(Boxy.isModalVisible()); return false;'>测试模态对话框</a></div>", {
							modal: true, behaviours: function(c) {
							  c.find("a:first").click(function() {
								newModal();
							  });
							}
						  });
					  };
					  
					  $("#modal").click(newModal);
					  
					  // No-show  
					  var noShow;
					  $("#no-show").click(function() {
						  noShow = new Boxy("<div>显示的内容</div>", {show: false});
						  return false;
					  });					  
					  $("#no-show-now").click(function() {
						  noShow.show();
						  return false;
					  });
					  
					  // Actuator		  
					  $("#actuator").click(function() {
						  var ele = $("#actuator-toggle")[0];
						  new Boxy("<div>测试内容</div>", {actuator: ele, show: false});
						  return false;
					  });
					  $("#actuator-toggle").click(function() {
						  Boxy.linkedTo(this).toggle();
						  return false;
					  });	  
					});*/
				 </script>
 </body> 
</html> 