<?php
/*
   * Modified: 2010-3-2
   * By: qianbo (qianbo@chd.edu.cn)
   *
   * Created: 2010-3-1
   * By: qianbo (qianbo@chd.edu.cn)
   *
*/ 
   require_once('_inc.php');
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<style>
html
{ 
  overflow-y:scroll;
  overflow-x:scroll;
  width:1000px;
}
</style>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>在线用户扫描</title>
<link href="../common/main.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../common/common.js"></script>
<script type="text/javascript" src="../js/ip_check.js"></script>
<script type="text/javascript">

String.prototype.trim   =   function()   
{   
	return   this.replace(/(^\s*)|(\s*$)/g,   "");   
} 

function InitAjax()
{
　var ajax=false; 
　try { 
　　ajax = new ActiveXObject("Msxml2.XMLHTTP"); 
　} catch (e) { 
　　try { 
　　　ajax = new ActiveXObject("Microsoft.XMLHTTP"); 
　　} catch (E) { 
　　　ajax = false; 
　　} 
　}
　if (!ajax && typeof XMLHttpRequest!='undefined') { 
　　ajax = new XMLHttpRequest(); 
　} 
　return ajax;
}

function check_ip()
{
	if(check_input()==false)
		return false;
	var ip_start=(document.getElementById("ip_start").value).trim();
	var ip_end=(document.getElementById("ip_end").value).trim();
	var url = "import_ip.php?ip_start="+ ip_start+"&ip_end="+ip_end;
	var ajax = InitAjax();
	ajax.open("GET", url, true); 
	ajax.send(null);
	ajax.onreadystatechange = function() 
	{ 
//		alert("receive the response");
		//如果执行是状态正常，那么就把返回的内容赋值给上面指定的层
		if (ajax.readyState == 4 ) 
		{ 
			var userip_check = ajax.responseText; 
			if(userip_check=="1")
			{
				alert("在线用户IP地址导入成功！");
			}
		} 
	}
}

function check_input()
{
	var ip_start=(document.getElementById("ip_start").value).trim();
	var ip_end=(document.getElementById("ip_end").value).trim();
	if(ip_start==""||ip_end=="")
	{
		alert("请输入要进行扫描的网段起始IP地址和结束IP地址");
		return false;
	}
	else
	{
		if(checkip(ip_start)==true&&checkip(ip_end)==true)
		{
			return true;
		}
		else
		{
			alert("IP地址输入格式错误，请重新输入");
			return false; 
		}
	}
}

</script>
</head>

<body>
<div class="bodyTitle">
	<div class="bodyTitleLeft"></div>
  <div class="bodyTitleText">在线用户扫描</div>
</div>
<br>
<div style="width:800px">
<form name="form_user" id="form_user" action="import_ip.php" method="post">
该页面将帮你导入在线的用户，以方便您的管理。您可以通过输入起始IP地址和结束IP地址来扫描该网段范围内的所有在线IP，并将其保存到用户列表中。

<br>
<br>
<br>
&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp起始IP地址：
<input type="text" id="ip_start"  name="ip_start" value="">
<font color=red>&nbsp&nbsp*您需要扫描网段的起始IP地址,格式如：192.168.0.1</font>
<br>
&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp结束IP地址：
<input type="text" id="ip_end"  name="ip_end" value="">
<font color=red>&nbsp&nbsp*您需要扫描网段的结束IP地址,格式如：192.168.0.254</font>
<br>
<br>
<font color=green>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp提示：如果网段过多，建议您分多次导入，每次IP地址范围过大的话可能会影响到您的机器性能。</font>
<br>
<br>
<input type="submit" style="width:70px;height:30px;float:left; margin-left:200px;"   name="Submit3" value="导入" onclick="return check_input();">
</form>
</div>
</body>

</html>

