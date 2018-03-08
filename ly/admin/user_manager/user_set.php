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
function long2ip_fix($ip_32)
{
	$ip = long2ip($ip_32);
	//先判断是big-endian还是little-endian
	$foo = 0x3456789a;
	switch (pack ('L', $foo)) 
	{
		case pack ('V', $foo):
			//little-endian
			$tmp = split("\.",$ip);
			$ip = $tmp[3].".".$tmp[2].".".$tmp[1].".".$tmp[0];
			break;
		case pack ('V', $foo):
			//big-endian
			//Nothing
			break;
		default:
	}
	return $ip;
}
if($_GET['new_user']==1)
{
	$user_name="";
	$user_account="";
	$user_password="";
	$user_ip="";
	$user_department_id="0";
	$user_specip = "2";
	$user_policy="0";
	$user_policy_select="";
	$_SESSION["create_user"] = 1;
	$_SESSION["user_id"] = 0;
}
else
{
	$_SESSION["create_user"] = 0;
	$sql = "select useraccount.*, netseg.name as dep_name, policy.name as policy_name from useraccount, 
		netseg, policy where useraccount.groupid=netseg.id and useraccount.policyid=policy.policyid and useraccount.account_id=".$_GET['id'];
	$arr = $db->fetchRows($sql);
	foreach($arr as $value)
	{
		$user_name=$value['name'];
		$_SESSION["user_id"] = $_GET['id'];
		$user_account=$value['account'];
		$user_password=$value['passwd'];
		if($value['bindip']=="0")
			$user_ip="";
		else
		{
			$user_ip=long2ip($value['bindip']);
		}
		$user_department_id=$value['groupid'];
		$user_specip = $value['specip'];
		if($user_specip==0||$user_specip==1)
		{
			$user_policy_select="disabled";
		}
		else
		{
			$dep_policy_select="";
		}
		$user_policy=$value['policyid'];
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
<title>用户管理</title>
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

function policy_control()
{
	if(document.getElementById("specip_select").value==2)
		document.getElementById("policy_select").disabled=false;
	else
		document.getElementById("policy_select").disabled=true;
}

function check_ip()
{
	var str_userip=(document.getElementById("user_ip").value).trim();
	if(checkip(str_userip)==true)
	{
		var url = "check_userip.php?userip="+ str_userip;
		var ajax = InitAjax();
		ajax.open("GET", url, true); 
		ajax.send(null);
		ajax.onreadystatechange = function() 
		{ 
//			alert("receive the response");
			//如果执行是状态正常，那么就把返回的内容赋值给上面指定的层
			if (ajax.readyState == 4 ) 
			{ 
				var userip_check = ajax.responseText; 
				if(userip_check=="1")
				{
					alert("您输入的IP地址已经存在于用户列表中，IP地址为用户的唯一标识，不允许重复");
					document.getElementById("user_ip").focus();
					return false;
				}
			} 
		}
	}
}

function check_input()
{
	var str_username=(document.getElementById("user_name").value).trim();
	var str_useraccount=(document.getElementById("user_account").value).trim();
	var str_userip=(document.getElementById("user_ip").value).trim();
	if(str_useraccount==""&&str_userip=="")
	{
		alert("您必须在用户IP地址和登陆账号中至少填写一项");
		document.getElementById("user_account").focus();
		return false;
	}
	if(str_userip=="")
	{
	}
	else
	{
		if(checkip(str_userip)==true)
		{
		}
		else
		{
			alert("IP地址输入格式错误，请重新输入");
			document.getElementById("user_ip").focus(); 
			return false; 
		}
	}
	if(str_username==""||checkip(str_username)==true)
	{
		if(str_userip=="")
		{
			document.getElementById("user_name").value=str_useraccount;
		}
		else
		{
			document.getElementById("user_name").value=str_userip;
		}
	}
	return true;
}

function cancel_edit()
{
	var answer = confirm("您确认要取消编辑吗？");
	if(answer)
		window.location.href="user_manager.php";
}

</script>
</head>

<body>
<div class="bodyTitle">
	<div class="bodyTitleLeft"></div>
  <div class="bodyTitleText">用户管理</div>
</div>
<br>
<div style="width:800px">
<form name="form_user" id="form_user" action="save_user.php" method="post">
该页面将帮你完成添加或者设置需要进行管理的用户信息，用户添加后意味着该用户被纳入管理范围，如不需要对其实施管理，可将其设置为"白名单"

<br>
<br>
<br>
&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp用户名称：
<input type="text" id="user_name"  name="username" value=<?php echo $user_name;?>>
<font color=green>&nbsp&nbsp您可以通过设置用户名称以方便您获取用户信息，建议您填写该信息。</font><br>
&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
<font color=green>如未填则该字段将被默认为IP地址或者用户账号。选填</font>
<br>
&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp登陆账号：
<input type="text" id="user_account"  name="useraccount" value=<?php echo $user_account;?>>
<font color=green>&nbsp    在账号模式下用户的登陆账号，如果采用动态分配IP地址的方式，将采用账号模式。选填</font>
<br>
<br>
&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp登陆密码：
<input type="text" id="user_password"  name="userpassword" value=<?php echo $user_password;?>>
<font color=green>&nbsp    在账号模式下用户的登陆账号的密码，选填</font>
<br>
<br>
&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp用户IP地址：
<input type="text" id="user_ip"  name="userip" value='<?php echo $user_ip;?>' onblur="check_ip()">
<font color=green>&nbsp    如果采用静态IP地址方式，每个用户将采用唯一的一个IP地址，如果为动态分配IP地址，</font><br>
&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
<font color=green>该字段可不填。请注意输入的IP地址格式，如：192.168.0.1&nbsp选填</font>
<br>
&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp所属网段：
<select   name="depart_select"   id="depart_select" > 
<?php 

$sql_userdep = "select id, name from netseg";
$arr_dep = $db->fetchRows($sql_userdep);
foreach($arr_dep as $value_dep)
{
	if($value_dep['id']==$user_department_id)
	{
		$dep_selected="selected";
	}
	else
		$dep_selected="";
?>
<option   value=<?php echo $value_dep['id'];?>   <?php echo $dep_selected;?>><?php echo $value_dep['name'];?></option> 
<?php 
}
unset($arr_dep);
?>
</select> 
<font color=green>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp 选择用户所属网段，可以默认为空</font>
<br>
<br>
&nbsp&nbsp&nbsp&nbsp黑白名单设置：
<select   name="specip_select"   id="specip_select"  onChange="policy_control()"> 
<option   value=0   <?php if($user_specip=="0") echo "selected";?>>黑名单</option>
<option   value=1   <?php if($user_specip=="1") echo "selected";?>>白名单</option>
<option   value=2   <?php if($user_specip=="2") echo "selected";?>>普通用户</option>
</select> 
<font color=green>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp 注意：设置用户的黑白名单属性，如果是黑名单用户，则该用户将被阻断，无法上网，如果为白名单</font><br>
&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
<font color=green>用户，则该用户将不被监控，对普通用户可以对其指定相应的策略来管理其上网行为。</font>
<br>
&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp采用策略：
<select   name="policy_select"   id="policy_select" <?php echo $user_policy_select;?>> 
<?php 
$sql_policy = "select policyid, name from policy where stat=1";
$arr_policy = $db->fetchRows($sql_policy);
foreach($arr_policy as $value_policy)
{
	if($value_policy['policyid']==$user_policy)
		$user_policy_select="selected";
	else
		$user_policy_select="";
?>
<option   value=<?php echo $value_policy['policyid'];?>   <?php echo $user_policy_select;?>><?php echo $value_policy['name'];?></option> 
<?php 
}
unset($arr_policy);
?>
</select> 
<font color=green>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp注意：策略仅可以分配给普通用户，对于黑/白名单用户，将无需对其设置策略</font>
<br>
<br>
<font color=red>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp注意：您至少应该在登陆账号和用户IP地址中填写其中一项信息。</font>
<br>
<br>
<br>
<br>
<input type="submit" style="width:70px;height:30px;float:left; margin-left:300px;"   name="Submit3" value="提交" onclick="return check_input();">
<input type="button" style="width:70px;height:30px; "  name="cancel" value="取消" onclick="cancel_edit();">
</form>
</div>
</body>

</html>

