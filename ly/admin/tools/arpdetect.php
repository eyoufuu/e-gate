<?php 
   require_once('_inc.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../common/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../common/common.js"></script>
<script src="../js/jquery.js" type="text/javascript"></script>
	<style type="text/css" title="currentStyle">
	@import "../js/js_new/demo_page.css";
	@import "../js/js_new/demo_table.css";
	</style>
<script>

var ipmacdata = new Array();
jQuery(document).ready(function(){

function onDataReceived(json_data)
{
	var i=0;
	for(i=0;i<json_data.ip.length;i++)
	{
		ipmacdata[json_data.ip[i]] = json_data.mac[i];
	}
}


 $.ajax({
        url: "./ipmacdata.php",
		cache:false,
        method: 'GET',
        dataType: 'json',
        success: onDataReceived
	});
});

</script>
<?php
	$result = $db->query2("SELECT ip,mac from ipmac","M",false);
	
?>
<script type="text/javascript">	
var rownum = 0;
var arpdata = new Array();


function get_arp_info()
{	
	function onDataReceived(json_data)
	{
		var i=0;
		for(i=0;i<json_data.length;i++)
		{
			var content = json_data[i].split(/\s+/,9);
			var rcvdata = new Array();
			
			if(content.length == 8)
			{				
				if(parseInt(content['7']) == 256)
				{					
					rcvdata.push(rownum,content['1'],content['2'],content['3'],content['4'],content['5'],content['6'],'询问');
					rownum++;
				}
				if(parseInt(content['7']) == 512)
				{					
					rcvdata.push(rownum,content['1'],content['2'],content['3'],content['4'],content['5'],content['6'],'应答');
					rownum++;
				}
				arpdata.unshift(rcvdata);
				if(arpdata.length>=30)
				{
					arpdata.pop();
				}					
			}			
		}
		for(i=0;i<arpdata.length;i++)
		{
			var col1 ="#000000";
			var col2 ="#000000";
			if(ipmacdata[arpdata[i]['5']])
			{
				if(ipmacdata[arpdata[i]['5']].toUpperCase().indexOf(arpdata[i]['2'])==0 && arpdata[i]['2'].toUpperCase().indexOf(ipmacdata[arpdata[i]['5']]) ==0)
				{
					col1 = "#000000";
				}
				else
				{
					col1 = "#FE2E2E";
				}
			}
			else
			{
				col1 = "#58ACFA";
			}
			if(arpdata[i]['7'].indexOf("应答")==0)
			{
				if(ipmacdata[arpdata[i]['6']])
				{
					if(ipmacdata[arpdata[i]['6']].toUpperCase().indexOf(arpdata[i]['4'])==0 && arpdata[i]['4'].toUpperCase().indexOf(ipmacdata[arpdata[i]['6']]) ==0)
					{
						col2 = "#000000";
					}
					else
					{
						col2 = "#0404B4";
					}
				}
				else
				{
					col2 = "#58ACFA";
				}
			}			
			$("#arp_show_info tr:eq("+i+") td:eq(0)").html(arpdata[i]['0']);
			$("#arp_show_info tr:eq("+i+") td:eq(1)").html(arpdata[i]['1']);
			$("#arp_show_info tr:eq("+i+") td:eq(2)").html("<font color='"+col1+"'>"+arpdata[i]['2']+"</font>");
			$("#arp_show_info tr:eq("+i+") td:eq(3)").html(arpdata[i]['3']);
			$("#arp_show_info tr:eq("+i+") td:eq(4)").html("<font color='"+col2+"'>"+arpdata[i]['4']+"</font>");
			$("#arp_show_info tr:eq("+i+") td:eq(5)").html("<font color='"+col1+"'>"+arpdata[i]['5']+"</font>");
			$("#arp_show_info tr:eq("+i+") td:eq(6)").html("<font color='"+col2+"'>"+arpdata[i]['6']+"</font>");
			$("#arp_show_info tr:eq("+i+") td:eq(7)").html(arpdata[i]['7']);
		}
	}
    $.ajax({
        url: "arpinfo_data.php",
		cache:false,
        method: 'GET',
        dataType: 'json',
        success: onDataReceived
	});
	setTimeout(get_arp_info, 3000);
}
//setTimeout(get_arp_info, 1000);
</script>
</head>
<body>
<h1>ARP分析和检测</h1>
<input class = "inputButton_in" type="button" value="开始" onclick="get_arp_info()" />
<input class = "inputButton_in" type="submit" value="结束" onclick="stop_arp()" />

	<table cellpadding="0" cellspacing="0" border="0"  class="display" id="arp_show_info">
	<thead>
	<tr><th align='center'>序号</th><th align='center'>目的MAC</th><th align='center'>源MAC</th><th align='center'>发送MAC</th><th align='center'>接收MAC</th><th align='center'>源IP</th><th>目的IP</th><th align='center'>类型</th></tr>
	</thead>
	<tbody>
	<script>
		var i=0;
		for(i=0;i<30;i++)
		{
			if(i%2==0)
			{
				document.write("<tr class='gradeA'><td align='center'>&nbsp;</td><td align='center'>&nbsp;</td><td align='center'>&nbsp;</td><td align='center'>&nbsp;</td><td align='center'>&nbsp;</td><td align='center'>&nbsp;</td><td align='center'>&nbsp;</td><td align='center'>&nbsp;</td></tr>");
			}
			else
			{
				document.write("<tr><td align='center'>&nbsp;</td><td align='center'>&nbsp;</td><td align='center'>&nbsp;</td><td align='center'>&nbsp;</td><td align='center'>&nbsp;</td><td align='center'>&nbsp;</td><td align='center'>&nbsp;</td><td align='center'>&nbsp;</td></tr>");
			}
		}
	</script>
	<tbody>
	<tfoot>
	<tr><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th></tr>
	</tfoot>
</table>
</body>
</html>