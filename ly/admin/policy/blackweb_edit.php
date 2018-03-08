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
if($_GET['new_blackweb']==1)
{
	$specweb="";
	$web_black_select="";
	$web_white_select="selected";
	$web_description="";
	$_SESSION["create_blackweb"] = 1;
	$_SESSION["blackweb_id"] = 0;
}
else
{
	$_SESSION["create_blackweb"] = 0;
	$sql = "select * from specweb where id=".$_GET['id'];
	$arr = $db->fetchRows($sql);
	foreach($arr as $value)
	{
		$specweb=$value['host'];
		$_SESSION["blackweb_id"] = $_GET['id'];
		if($value['pass']==1)
		{
			$web_black_select="";
			$web_white_select="selected";
		}
		else
		{
			$web_black_select="selected";
			$web_white_select="";
		}
		$web_description=$value['description'];
	}
	unset($arr);
	$db->close();
}
?> 

<style>
.specweb{width:300px;}
.description{width:300px;height:100px}
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
<title>黑白网址编辑</title>
<link href="../common/main.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../common/common.js"></script>
<script type="text/javascript" src="ip_check.js"></script>
<script type="text/javascript">

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

String.prototype.trim   =   function()   
{   
	return   this.replace(/(^\s*)|(\s*$)/g,   "");   
}   

function input_limit(obj, length)
{
	if(obj.value.length>length)
	{
		obj.value = obj.value.substring(0,length); 
	}
}

function check_input()
{
	var str_hostname=(document.getElementById("webname").value).trim();
	if(str_hostname=="")
	{
		alert("站点网址不允许为空，请输入！");
		document.getElementById("webname").focus();
		return false;
	}
	var url = "check_host.php?host="+ str_hostname;
	var ajax = InitAjax();
	ajax.open("GET", url, true); 
	ajax.send(null);
	ajax.onreadystatechange = function() 
	{ 
//		alert("receive the response");
		//如果执行是状态正常，那么就把返回的内容赋值给上面指定的层
		if (ajax.readyState == 4 ) 
		{ 
			var host_check = ajax.responseText; 
			if(host_check=="1")
			{
				alert("您输入的站点网址已经存在！");
				document.getElementById("webname").focus();
				return false;
			}
			else
			{
				document.form_specweb.submit();
			}
		} 
	}
//	document.form_specweb.submit();
}

function cancel_edit()
{
	var answer = confirm("您确认要取消编辑吗？");
	if(answer)
	{
		var a=0;
		window.returnValue = a 
		window.close();
	}
}

</script>
<base target="_self"/>
</head>

<body>
<br>
<div style="width:800px">
<form name="form_specweb" id="form_specweb" action="save_blackweb.php" method="post">

&nbsp&nbsp&nbsp站点网址：
<input type="text" class="specweb" id="web_name"  name="webname" onkeyup="input_limit(this, 64)" value=<?php echo $specweb;?>>
<br>
&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<font color=red>*    所要加入黑/白名单的站点网址，您可以输入简短的网址，<br> 
&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp例如要将新浪网加入黑/白名单，您只要输入sina即可。必填</font>
<br>
<br>
&nbsp&nbsp阻挡/放行：
<select   name="web_select"   id="web_select" > 
<option   value=0   <?php echo $web_black_select;?>>阻挡</option> 
<option   value=1   <?php echo $web_white_select;?>>放行</option> 
</select> 
<font color=red>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp注意：该设置将对所有策略有效</font>
<br>
<br>
&nbsp&nbsp&nbsp站点描述：<font color=green>    所要加入黑/白名单的站点的简短描述，不超过30个字符</font>
<br>
&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
<textarea id="web_description"  name="webdescription" style="width:250px; height:60px;overflow-y:hidden ;" onkeyup="input_limit(this, 30)" ><?php echo $web_description;?></textarea>
<br>
<br>
<br>
<input type="button" style="width:70px;height:30px; float:left; margin-left:150px;"   name="Submit3" value="提交" onclick="return check_input();">
<input type="button" style="width:70px;height:30px; "  name="cancel" value="取消" onclick="cancel_edit();">
</form>
</div>
</body>

</html>

