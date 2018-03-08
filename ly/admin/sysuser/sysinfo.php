<?php
   require_once('_inc.php');
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link href="../common/main.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="../common/common.js"></script>
	<script type="text/javascript" src="table_set.js"></script>
	<script src="../js/jquery.js" type="text/javascript"></script>
	<style type="text/css" title="currentStyle">
			@import "../js/js_new/demo_page.css";
			@import "../js/js_new/demo_table.css";
	</style>
</head>


<script type="text/javascript">

function getcpuused()
{
	var str = <?php 
	$return_array = array();
	exec("sed -n '2,5p' /proc/stat |grep '^cpu'", $return_array);
	$str = json_encode($return_array);
	echo $str;
	?>;
	for(i=0;i<str.length;i++)
	{
		var content = str[i].split(" ",5);
		var total = parseInt(content['1'])+parseInt(content['2'])+parseInt(content['3'])+parseInt(content['4']);
		var used = (1-parseInt(content['4'])/total)*100;
		document.write(content['0']+': '+ used.toFixed(2)+'&nbsp');
	}
}
function getmeminfo()
{
	var str = <?php 
	$return_array = array();
	exec("sed -n '1,2p' /proc/meminfo", $return_array);
	$str = json_encode($return_array);
	echo $str;
	?>;
	for(i=0;i<str.length;i++)
	{
		var content = str[i].split(/\s+/,3);
		document.write('内存'+': '+ content['1']+content['2']);
	}
	return str;
}
function getdiskinfo()
{
	var str = <?php 
			$return_array = array();
			exec("df -hl |grep '/$'", $return_array);
			$str = json_encode($return_array);
			echo $str;
			?>;

	var content = str['0'].split(/\s+/,4);
	document.write('容量:'+content['0']+' 已用:'+content['1']+' 可用:'+content['2']+' 已用(%):'+content['3']);

}
//window.setInterval( function(){ getmeminfo(); },1000);
//getmeminfo();
//getcpuused();
//getdiskinfo();



</script>
<script type="text/javascript">
jQuery(document).ready(function(){

	var cpu =<?php 
	$return_array = array();
	exec("sed -n '1,5p' /proc/stat |grep '^cpu'", $return_array);
	$str = json_encode($return_array);
	echo $str;
	?>;
	
	function get_sys_info()
	{	
		var  i=1;
		//alert(cpu1);
		//alert(cpu2);
		function onDataReceived(json_data)
		{
			for(j=0;j<json_data.cpu.length;j++)
			{
				var c2 = json_data.cpu[j].split(/\s+/,5);
				var t2 = parseInt(c2['1'])+parseInt(c2['2'])+parseInt(c2['3'])+parseInt(c2['4']);

				var c1 = cpu[j].split(/\s+/,5);
				var t1 = parseInt(c1['1'])+parseInt(c1['2'])+parseInt(c1['3'])+parseInt(c1['4']);
				
				var free = (parseInt(c2['4'])-parseInt(c1['4']))/(t2-t1);			
				var used = 100*(1-free);

				$("#sys_show_info tr:eq("+i+") td:eq(0)").html(c2['0']);
				$("#sys_show_info tr:eq("+i+") td:eq(1)").html(used.toFixed(2)+'%');
				$("#sys_show_info tr:eq("+i+") td:eq(2)").html('--');			
				$("#sys_show_info tr:eq("+i+") td:eq(3)").html('--');
				$("#sys_show_info tr:eq("+i+") td:eq(4)").html('--');
				i++;
			}
			cpu = json_data.cpu;
			$("#sys_show_info tr:eq("+i+") td:eq(0)").html('内存');
			$("#sys_show_info tr:eq("+i+") td:eq(1)").html('--');
			var mem1 = json_data.mem[0].split(/\s+/,3);			
			$("#sys_show_info tr:eq("+i+") td:eq(2)").html(mem1['1']+' '+mem1['2']);
			var mem2 = json_data.mem[1].split(/\s+/,3);			
			$("#sys_show_info tr:eq("+i+") td:eq(4)").html(mem2['1']+' '+mem2['2']);
			var memused = parseInt(mem1['1'])-parseInt(mem2['1']);		
			$("#sys_show_info tr:eq("+i+") td:eq(3)").html(memused+' '+mem1['2']);	
			i++;
			$("#sys_show_info tr:eq("+i+") td:eq(0)").html('硬盘');
			var disk = json_data.disk[0].split(/\s+/,5);	
			$("#sys_show_info tr:eq("+i+") td:eq(1)").html(disk['4']);
					
			$("#sys_show_info tr:eq("+i+") td:eq(2)").html(disk['1']);
				
			$("#sys_show_info tr:eq("+i+") td:eq(4)").html(disk['3']);
				
			$("#sys_show_info tr:eq("+i+") td:eq(3)").html(disk['2']);
		}
        $.ajax({
            url: "sysinfo_data.php",
			cache:false,
            method: 'GET',
            dataType: 'json',
            success: onDataReceived
    	});
		setTimeout(get_sys_info, 3000);
	}
		//alert("start");
		setTimeout(get_sys_info, 1000);
});
	

</script>
<body>
	<h1>系统信息</h1>	
	<table cellpadding="0" cellspacing="0" border="0"  class="display" id="sys_show_info">
<!-- 	<caption align="left"><font color="red">系统信息</font></caption> -->
	<thead><tr><th>类别</th><th>利用率</th><th>容量</th><th>已用</th><th>剩余</th></tr></thead>
	<tbody>
	<tr class="gradeA"><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td></tr>
	<tr><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td></tr>
	<tr class="gradeA"><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td></tr>
	<tr><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td></tr>
	<tr class="gradeA"><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td></tr>
	<tr><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td></tr>
	<tr class="gradeA"><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td></tr>
	<tr><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td></tr>
	<tr class="gradeA"><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td></tr>
	<tr><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="right">&nbsp;</td><td align="center">&nbsp;</td></tr>
	<tbody>
	<tfoot>
	<tr><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th></tr>
	</tfoot>
	</table>

<script language="javascript">
//	senfe("sys_show_info","#f8fbfc","#e5f1f4","#ecfbd4","#bce774");
</script>
<?php 
/*while
$return_array = array();
exec("sed -n '2,5p' /proc/stat |grep '^cpu'", $return_array);
$str = json_encode($return_array);
//echo "<pre>"; 
//foreach($return_array as $value)
//	echo $value;
//print_r($return_array);
//echo "</pre>"; 
count($return_array);
echo <<<eot
<script>
	var str= $str;

	document.write(str['0']);
	</script>

eot;*/
?>


</body>
</html>