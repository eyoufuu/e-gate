<?php 
   require_once('_inc.php');
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../common/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../common/common.js"></script>
<script src="../js/jquery.js" type="text/javascript"></script>
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
					rcvdata.push(content['1'],content['2'],content['3'],content['4'],content['5'],content['6'],'询问');
				}
				if(parseInt(content['7']) == 512)
				{					
					rcvdata.push(content['1'],content['2'],content['3'],content['4'],content['5'],content['6'],'应答');
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
			$("#arp_show_info tr:eq("+i+") td:eq(0)").html(arpdata[i]['0']);
			$("#arp_show_info tr:eq("+i+") td:eq(1)").html(arpdata[i]['1']);
			$("#arp_show_info tr:eq("+i+") td:eq(2)").html(arpdata[i]['2']);
			$("#arp_show_info tr:eq("+i+") td:eq(3)").html(arpdata[i]['3']);
			$("#arp_show_info tr:eq("+i+") td:eq(4)").html(arpdata[i]['4']);
			$("#arp_show_info tr:eq("+i+") td:eq(5)").html(arpdata[i]['5']);
			$("#arp_show_info tr:eq("+i+") td:eq(6)").html(arpdata[i]['6']);
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
<h1>ARP分析和检测</h1>
<input class = "inputButton_in" type="button" value="开始" onclick="get_arp_info()" />
<input class = "inputButton_in" type="submit" value="结束" onclick="stop_arp()" />
</head>
<body>
<table  cellpadding="0" cellspacing="0" border="0"  id="arp_show_info" width="100%">
	<tr><th>目的MAC</th><th>源MAC</th><th>发送MAC</th><th>接收MAC</th><th>源IP</th><th>目的IP</th><th>类型</th></tr>
	<tbody>
	<script>
		var i=0;
		for(i=0;i<30;i++)
		{
			document.write("<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>");
		}
	</script>
	<tbody>
</table>
</body>
</html>