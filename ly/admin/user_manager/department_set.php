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

<?php
if($_GET['new_department']==1)
{
	$depart_name="";
	$ip_start="";
	$ip_end="";
	$dep_monitor="";
	$dep_policy="0";
	$dep_policy_select="disabled";
	$_SESSION["create_department"] = 1;
	$_SESSION["department_id"] = 0;
}
else
{
	$_SESSION["create_department"] = 0;
	$sql = "select netseg.id as id, netseg.name as netseg_name, netseg.ips as ips, netseg.ipe as ipe,
			netseg.monitor as monitor, netseg.policyid as policyid, policy.name as policy_name from netseg,
			policy where netseg.policyid=policy.policyid and id=".$_GET['id'];
	$arr = $db->fetchRows($sql);
	foreach($arr as $value)
	{
		$depart_name=$value['netseg_name'];
		$_SESSION["department_id"] = $_GET['id'];
		$ip_start=long2ip($value['ips']);
		$ip_end=long2ip($value['ipe']);
		if($value['monitor']==1)
		{
			$dep_policy_select="";
			$dep_monitor="checked";
		}
		else
		{
			$dep_policy_select="disabled";
			$dep_monitor="";
		}
		$dep_policy=$value['policyid'];
	}
}
?> 

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
<title>网段管理</title>
<link href="../common/main.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../common/common.js"></script>
<script type="text/javascript" src="../js/ip_check.js"></script>
<script type="text/javascript">

String.prototype.trim   =   function()   
{   
	return   this.replace(/(^\s*)|(\s*$)/g,   "");   
}  
function cancel_edit()
{
	var answer = confirm("您确认要取消编辑吗？");
	if(answer)
		window.location.href="department_manager.php";
}
/*
function checkip(ip_addr)   
{  
	alert("sdlkjgf ");
	return false;
	var scount=0;    
	var iplength = ip.length;   
	var Letters = "1234567890.";   
	for (i=0; i < ip_addr.length; i++)   
	{   
		var CheckChar = ip_addr.charAt(i);   
		if (Letters.indexOf(CheckChar) == -1)
			return false;
	}
	for (var i = 0;i<iplength;i++)   
		(ip.substr(i,1)==".")?scount++:scount;   
	if(scount!=3)   
		return false;
	first = ip.indexOf(".");   
	last = ip.lastIndexOf(".");   
	str1 = ip.substring(0,first);   
	subip = ip.substring(0,last);   
	sublength = subip.length;   
	second = subip.lastIndexOf(".");   
	str2 = subip.substring(first+1,second);   
	str3 = subip.substring(second+1,sublength);   
	str4 = ip.substring(last+1,iplength);   
     
	if (str1=="" str2=="" str3== "" str4 == "")   
		return false;   
	if (str1< 0 str1 >255)   
		return false;    
	else if (str2< 0 str2 >255)   
		return false;   
	else if (str3< 0 str3 >255)   
		return false;   
	else if (str4< 0 str4 >255)   
		return false;   
	return true;
}*/

function policy_control()
{
	if(document.getElementById("monitor").checked==true)
		document.getElementById("policy_select").disabled=false;
	else
		document.getElementById("policy_select").disabled=true;
}

function check_input()
{
	var str_depname=(document.getElementById("department_name").value).trim();
	if(str_depname=="")
	{
		alert("网段名称不允许为空，请输入！");
		return false;
	}
	var ip_start=(document.getElementById("ip_start").value).trim();
	if(checkip(ip_start)==true)
	{
	}
	else
	{
		alert("IP地址输入格式错误，请重新输入");
		document.getElementById("ip_start").focus(); 
		return false; 
	}
	var ip_end=(document.getElementById("ip_end").value).trim();
	if(checkip(ip_end)==true)
	{
	}
	else
	{
		alert("IP地址输入格式错误，请重新输入");
		document.getElementById("ip_end").focus(); 
		return false; 
	}
	var arr_ipstart=ip_start.split(".");
	var arr_ipend=ip_end.split(".");
	if(parseInt(arr_ipstart[0])>parseInt(arr_ipend[0]))
	{
		alert("起始IP地址不能大于结束IP地址，请重新输入");
		document.getElementById("ip_start").focus(); 
		return false;
	}
	else if(parseInt(arr_ipstart[0])==parseInt(arr_ipend[0]))
	{
		if(parseInt(arr_ipstart[1])>parseInt(arr_ipend[1]))
		{
			alert("起始IP地址不能大于结束IP地址，请重新输入");
			document.getElementById("ip_start").focus(); 
			return false;
		}
		else if(parseInt(arr_ipstart[1])==parseInt(arr_ipend[1]))
		{
			if(parseInt(arr_ipstart[2])>parseInt(arr_ipend[2]))
			{
				alert("起始IP地址不能大于结束IP地址，请重新输入");
				document.getElementById("ip_start").focus(); 
				return false;
			}
			else if(parseInt(arr_ipstart[2])==parseInt(arr_ipend[2]))
			{
				if(parseInt(arr_ipstart[3])>parseInt(arr_ipend[3]))
				{
					alert("起始IP地址不能大于结束IP地址，请重新输入");
					document.getElementById("ip_start").focus(); 
					return false;
				}
			}
		}
	}
	return true;
}

</script>
</head>

<body>
<div class="bodyTitle">
	<div class="bodyTitleLeft"></div>
  <div class="bodyTitleText">网段管理</div>
</div>
<br>
<div style="width:800px">
<form name="form_department" id="form_department" action="save_department.php" method="post">
该页面将帮你添加或者编辑网段信息，您可以添加或者编辑您所想要管理的网段

<br>
<br>
<br>
&nbsp&nbsp网段名称：
<input type="text" id="department_name"  name="departmentname" value=<?php echo $depart_name;?>>
<font color=red>*    您可以对需要进行管理的网段设置一个名称，比如对应的部门名称等。必填</font>
<br>
<br>
起始IP地址：
<input type="text" id="ip_start"  name="ipstart" value=<?php echo $ip_start;?>>
<font color=red>*    该网段的起始IP地址，请注意输入的IP地址格式，如：192.168.0.1&nbsp必填</font>
<br>
<br>
结束IP地址：
<input type="text" id="ip_end"  name="ipend" value=<?php echo $ip_end;?>>
<font color=red>*    该网段的结束IP地址，请注意输入的IP地址格式，如：192.168.0.1&nbsp必填</font>
<br>
<br>
&nbsp&nbsp是否监控：
&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
<input type="checkbox" name="monitor" id="monitor" value=1 <?php echo $dep_monitor;?> onclick="policy_control()">
<font color=red>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp注意：如未将该网段纳入管理范围，也可以对该网段内的IP地址单独设置管理策略</font>
<br>
<br>
&nbsp&nbsp采用策略：
<select   name="policy_select"   id="policy_select" <?php echo $dep_policy_select;?>> 
<?php 
$sql_policy = "select policyid, name from policy where stat=1";
$arr_policy = $db->fetchRows($sql_policy);
foreach($arr_policy as $value_policy)
{
	if($value_policy['policyid']==$dep_policy)
		$policy_selected="selected";
	else
		$policy_selected="";
?>
<option   value=<?php echo $value_policy['policyid'];?>   <?php echo $policy_selected;?>><?php echo $value_policy['name'];?></option> 
<?php 
}
unset($arr_policy);
?>
</select> 
<font color=red>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp注意：对网段设置的策略只对IP地址在该网段范围内并且未加入到用户管理列表的IP地址有效，</font><br>
&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
<font color=red>对于加入到用户列表中的IP地址在用户管理列表中可对其单独设置策略</font>
<br>
<br>
<br>
<br>
<input type="submit" style="width:70px;height:30px;float:left; margin-left:100px;"   name="Submit3" value="提交" onclick="return check_input();">
<input type="button" style="width:70px;height:30px; "  name="cancel" value="取消" onclick="cancel_edit();">
</form>
</div>
</body>

</html>

