<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html> 
 <head> 
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
    <title>实时流量分析-网络协议流量前十名</title> 
	<link href="../common/main.css" rel="stylesheet" type="text/css"/>
	<script type="text/javascript" src="../common/common.js"></script>
	<script src="../js/jquery.js" type="text/javascript"></script>
	<script src="../js/jscharts_mb.js" type="text/javascript"></script>
	<script type="text/javascript" src="table_set.js"></script>
	
<script type="text/javascript">
jQuery(document).ready(function(){
//jQuery("#ips_list").jqGrid('navGrid','#pager',{edit:false,add:false,del:false,search: false});
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
			$("#data_show_pro_top tr:eq("+x+") td:eq(1)").html(toppro[i][3]);//ip地址赋值		
			$("#data_show_pro_top tr:eq("+x+") td:eq(2)").html(toppro[i][0]);//上行流量		
			$("#data_show_pro_top tr:eq("+x+") td:eq(3)").html(toppro[i][1]);//下行流量		
			$("#data_show_pro_top tr:eq("+x+") td:eq(4)").html(toppro[i][2]);//总流量		
			$("#data_show_pro_top tr:eq("+x+") td:eq(5)").html("<a href='ip_flow_detail.php?ip='"+toppro[i][3]+ ">详细</a>");
		}
	}	
	
   function fetchData_ip()
   {
    function onDataReceived(data_ip_pro)
	{
	    //alert(data_ip_pro.ip);
		var topip= data_ip_pro.ip;
		var toppro = data_ip_pro.pro;
		var piedata=[];
		for(var i=0;i<toppro.length;i++)
		{
			piedata.push([toppro[i][3],parseFloat(toppro[i][2])]);
		}
		pie_pro_top10.setDataArray(piedata);
		pie_pro_top10.draw();
		fetchData_pro_table_data(toppro);	
	}//onDataReceived end;
    $.ajax({
            url: "get_tc_top10_ip.php",
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
	
	
	
	
});

</script>
</head> 
<body> 
<div class="bodyTitle">
 <div class="bodyTitleLeft"></div>
 <div class="bodyTitleText">流量实时展示-TOP_IP</div>
</div>

    <div id="pro_top_10" class="leftfloat" style="padding:5px"></div>
	<div id = "pro_top_10_data" class = "right" >
	<table class="warp_table" id="data_show_pro_top">
	<caption>3秒中统计值--网络出口最高得10个协议流量表</caption>
	<thead><tr><th>排名号</th><th>协议名称</th><th>上行流量</th><th>下行流量</th><th>总流量</th><th>详细</th></tr></thead>
	<tr><td align="center">1</td><td>&nbsp;</td><td align="right">&nbsp;</td><td align="right">&nbsp;</td><td align="right">&nbsp;</td><td align="center">&nbsp;</td></tr>
	<tr><td align="center">2</td><td>&nbsp;</td><td align="right">&nbsp;</td><td align="right">&nbsp;</td><td align="right">&nbsp;</td><td align="center">&nbsp;</td></tr>
	<tr><td align="center">3</td><td>&nbsp;</td><td align="right">&nbsp;</td><td align="right">&nbsp;</td><td align="right">&nbsp;</td><td align="center">&nbsp;</td></tr>
	<tr><td align="center">4</td><td>&nbsp;</td><td align="right">&nbsp;</td><td align="right">&nbsp;</td><td align="right">&nbsp;</td><td align="center">&nbsp;</td></tr>
	<tr><td align="center">5</td><td>&nbsp;</td><td align="right">&nbsp;</td><td align="right">&nbsp;</td><td align="right">&nbsp;</td><td align="center">&nbsp;</td></tr>
	<tr><td align="center">6</td><td>&nbsp;</td><td align="right">&nbsp;</td><td align="right">&nbsp;</td><td align="right">&nbsp;</td><td align="center">&nbsp;</td></tr>
	<tr><td align="center">7</td><td>&nbsp;</td><td align="right">&nbsp;</td><td align="right">&nbsp;</td><td align="right">&nbsp;</td><td align="center">&nbsp;</td></tr>
	<tr><td align="center">8</td><td>&nbsp;</td><td align="right">&nbsp;</td><td align="right">&nbsp;</td><td align="right">&nbsp;</td><td align="center">&nbsp;</td></tr>
	<tr><td align="center">9</td><td>&nbsp;</td><td align="right">&nbsp;</td><td align="right">&nbsp;</td><td align="right">&nbsp;</td><td align="center">&nbsp;</td></tr>
	<tr><td align="center">10</td><td>&nbsp;</td><td align="right">&nbsp;</td><td align="right">&nbsp;</td><td align="right">&nbsp;</td><td align="center">&nbsp;</td></tr>
	<tr><td align="center">统计</td><td>--</td><td align="right">&nbsp;</td><td align="right">&nbsp;</td><td align="right">&nbsp;</td><td align="center">--</td></tr>
	</table>
	</div>
 <script language="javascript"><!--
senfe("data_show_pro_top","#f8fbfc","#e5f1f4","#ecfbd4","#bce774");
--></script>
 </body> 
</html> 