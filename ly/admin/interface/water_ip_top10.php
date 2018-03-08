<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html> 
 <head> 
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
    <title>实时流量分析-网络IP流量前十名</title> 
	<link href="../common/main.css" rel="stylesheet" type="text/css"/>
	<link href="layout.css" rel="stylesheet" type="text/css"></link>
    <!--link href="layout.css" rel="stylesheet" type="text/css"></link>--> 
    <!--[if IE]><script language="javascript" type="text/javascript" src="js1/excanvas.min.js"></script><![endif]--> 
	<script src="js/jquery.js" type="text/javascript"></script>
    <script language="javascript" type="text/javascript" src="js1/jquery.flot.js"></script> 
	<script language="javascript" type="text/javascript" src="js1/jquery.flot.stack.js"></script>
	<script language="javascript" type="text/javascript" src="table_set.js"></script>
	
<script type="text/javascript">

jQuery(document).ready(function(){
//jQuery("#ips_list").jqGrid('navGrid','#pager',{edit:false,add:false,del:false,search: false});
	$(function () {
   	function fetchData_ip_table_data(topip)
    {	
	    var x = 0;
		for(var i=0;i<topip.length;i++)
		{
		    x = i+1;
			$("#data_show_ip_top tr:eq("+x+") td:eq(1)").html("192.168.0."+topip[i][3]);//ip地址赋值		
			$("#data_show_ip_top tr:eq("+x+") td:eq(2)").html(topip[i][0]);//上行流量		
			$("#data_show_ip_top tr:eq("+x+") td:eq(3)").html(topip[i][1]);//下行流量		
			$("#data_show_ip_top tr:eq("+x+") td:eq(4)").html(topip[i][2]);//总流量		
			$("#data_show_ip_top tr:eq("+x+") td:eq(5)").html("<a href='ip_flow_detail.php?ip='"+topip[i][3]+ ">详细</a>");
		}
	}
	
   function fetchData_ip()
   {
    function onDataReceived(data_ip_pro)
	{
	    //alert(data_ip_pro.ip);
		var topip= data_ip_pro.ip;
		var toppro = data_ip_pro.pro;
		var d1 = [];
		var d2 = [];
		for(var i=0;i<topip.length;i++)
		{
			d1.push([i,topip[i][0]]);
			d2.push([i,topip[i][1]]);
		}
        $.plot($("#ip_top_10"), [ {label: "上行流量",data:d1,color:"rgb(10, 255, 5)"}, {label: "下行流量",data:d2,color:"rgb(255, 0, 0)"} ], {
            series: {
                stack: true,
               // lines: { show: lines, steps: steps },
                bars: { show: true, barWidth: 0.6 }
            }
			});
		fetchData_ip_table_data(topip);	
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
 <p> 
	  <input class="ip_pro" type="button" value="10用户流量" /> 	
</p> 

    <div id="ip_top_10" class="leftfloat" style="padding:5px"></div>
	<div id = "ip_top_10_data" class = "right" >
	<table class="warp_table" id="data_show_ip_top">
	<caption align="right"><font color="red">3秒中统计值--网络中最高得10个用户流量表</font></caption>
	<thead><tr><th>排名号</th><th>IP地址</th><th>上行流量</th><th>下行流量</th><th>总流量</th><th>详细</th></tr></thead>
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

 <script language="javascript">
senfe("data_show_ip_top","#f8fbfc","#e5f1f4","#ecfbd4","#bce774");
</script>
 </body> 
</html> 