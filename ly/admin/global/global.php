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
	<script language="javascript" type="text/javascript" src="../js/calenderJS.js" defer="defer"></script>
	
</head>
<?php
      
 
  $sql_log="select systemmode,isremindpage,isipmacbind,gate from globalpara;";
  $result = $db->query2($sql_log,"全局设置",true);
  
  $loginmode = $result['0']['systemmode'];
  $isremind = $result['0']['isremindpage'];
  $isipmacbind =  $result['0']['isipmacbind'];
  $gate = $result['0']['gate'];
?>

<script type="text/javascript">

function isValidDate(str)
{
	if (!/^\d{4}\-\d\d?\-\d\d?/.test(str)) {
		return false;
	}
	var array = str.replace(/\-0/g, "-").split("-");
	var year = parseInt(array[0]);
	var month = parseInt(array[1]) - 1;
	var day = parseInt(array[2]);
	var date = new Date(year, month, day);
	return (date.getFullYear() == year && 
			date.getMonth() == month && 
			date.getDate() == day);
}
function time_submit()
{
	var date = $("#date").val();
	if(!isValidDate(date))
	{
		alert("日期输入错误");
		return false;
	}
	var hour = $("#hour").val();
	var min =  $("#min").val();
	var sec = $("#sec").val();
	if(hour == "" || min==""||sec=="")
	{
		alert("时间输入不能为空");
		return false;
	}	
	if(parseInt(hour)>23 || parseInt(min)>59 ||parseInt(sec)>59)
	{
		alert("时间输入错误");
		return false;
	}
	var time = hour+":"+min+":"+sec;
	$.post("systime_save.php",{d:date,t:time});
	alert("设置成功");
}
function get_time_info()
{
	function onDataReceived(json_data)
	{
		var data = json_data.split(" ");
		var str;
		switch(data[2])
		{
		case "1":
			str = "星期一";
			break;
		case "2":
			str = "星期二";
			break;
		case "3":
			str = "星期三";
			break;
		case "4":
			str = "星期四";
			break;
		case "5":
			str = "星期五";
			break;
		case "6":
			str = "星期六";
			break;
		case "7":
			str = "星期日";
			break;
		}
		$("#show").attr("value",data[0]+" "+data[1]+" "+str);		
	}
    $.ajax({
        url: "systime_data.php",
		cache:false,
        method: 'GET',
        dataType: 'json',
        success: onDataReceived
	});
	setTimeout(get_time_info, 3000);
}
get_time_info();
</script>
<script>
function remindpage_submit()
{
	var val;
	if($("#isremind").attr("checked")==true)
	{
		val = 1;
	}
	else
	{
		val =0;
	}
	$.post("global_remindpage.php",{remind:val});
	alert("设置成功");
}
function mode_submit()
{
	var val;
	if($("#mode").attr("checked")==true)
	{
		val =0;
	}
	else
	{
		val=1;
	}
	$.post("global_loginmode.php",{radio:val});
	alert("设置成功");
}
function  bypass_submit()
{
	var val;
	if($("#bypass0").attr("checked")==true)
	{
		val =0;
	}
	if($("#bypass1").attr("checked")==true)
	{
		val =1;
	}
	if($("#bypass2").attr("checked")==true)
	{
		val =2;
	}
	if($("#bypass3").attr("checked")==true)
	{
		val =3;
	}
	$.post("global_bypass.php",{radio:val});
	alert("设置成功");
	
}
function gate_submit()
{
	var val;
	if($("#gate").attr("checked")==true)
	{
		val = 1;
	}
	else
	{
		val =0;
	}
	$.post("global_gate.php",{gate:val});
	alert("设置成功");
}
</script>
<body>
<h1>系统时间设置</h1>
当前时间：<input id="show" readonly ="readonly" style="width:160px;" name="show" type="text" value="" />
<br>
设置日期：<input id="date" name="date" type="text" style="width:160px" onfocus="HS_setDate(this)"	value="" />

设置时间：<input id="hour" type="text" style="width:30px" value="" />:<input id="min" type="text" style="width:30px" value=""  />:<input id="sec" type="text" style="width:30px"  value="" />
<br/>
<input class = "inputButton_in" style="margin-left:500px;" type="button" name="提交" value="提交" onclick="return time_submit()" />
<h1>客户端登陆方式</h1>
	 <table border="0" cellpadding="0" cellspacing="0">
         <tr width='20px'>
		   <td><input type="radio" name="radio1" id="mode"  value="1" <?php if($loginmode=='0') echo 'checked';?> /></td>
		   <td align="left">IP方式</td>
		 </tr>
        <tr width='20px'>
		 <td><input type="radio" name="radio1" id="userid"  value="2" <?php if($loginmode=='1') echo 'checked';?> /></td>
		 <td align="left">帐号方式</td>
		</tr>
	</table>

<INPUT class = "inputButton_in" style="margin-left:500px;" type="button" name="提交" value="提交" id="loginmode" size="20" onclick="return mode_submit();" />	

<h1>未监控网段和IP设置</h1>
      <table border="0" cellpadding="0" cellspacing="0">
        <tr width='20px'>
		   <td><input type="checkbox" name="gate" id="gate"  value="1" <?php if($gate=='1') echo 'checked';?> /></td>
		   <td align="left">阻挡未监控的网段和IP</td>
		</tr>
      </table>
 
<INPUT class = "inputButton_in" style="margin-left:500px;" type="button" name="提交" value="提交" id="gage" size="20" onclick="return gate_submit();" />
	

<h1>提示阻挡页面</h1>
      <table border="0" cellpadding="0" cellspacing="0">
        <tr width='20px'>
		   <td><input type="checkbox" name="check" id="isremind"  value="1" <?php if($isremind=='1') echo 'checked';?>  /></td>
		   <td align="left">当请求的页面被阻挡时，是否向用户提示阻挡页面</td>
		</tr>
      </table>
 
<INPUT class = "inputButton_in" style="margin-left:500px;" type="button" name="提交" value="提交" id="remindpage" size="20" onclick="return remindpage_submit();" />
	
<h1>BYPASS</h1>
<table border="0" cellpadding="0" cellspacing="0">
         <tr width='20px'>
		   <td><input type="radio" name="radio" id="bypass0"  value="0" <?php if($isipmacbind=='0') echo 'checked';?> /></td>
		   <td align="left">开启 BYPASS IPMAC不绑定</td>
		 </tr>
        <tr width='20px'>
		 <td><input type="radio" name="radio" id="bypass1"  value="1" <?php if($isipmacbind=='1') echo 'checked';?> /></td>
		 <td align="left">关闭 BYPASS IPMAC不绑定</td>
		</tr>
		<tr width='20px'>
		   <td><input type="radio" name="radio" id="bypass2"  value="2" <?php if($isipmacbind=='2') echo 'checked';?> /></td>
		   <td align="left">开启 BYPASS IPMAC绑定</td>
		 </tr>
		 <tr width='20px'>
		   <td><input type="radio" name="radio" id="bypass3"  value="3" <?php if($isipmacbind=='3') echo 'checked';?> /></td>
		   <td align="left">关闭 BYPASS IPMAC绑定</td>
		 </tr>
	</table>
<INPUT class = "inputButton_in" style="margin-left:500px;" type="button" name="提交" value="提交" id="bypass" size="20" onclick="return bypass_submit();" />	

</body>
</html>