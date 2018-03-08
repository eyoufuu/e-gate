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
<body>
<h1>当前系统时间</h1>
<input id="show" readonly ="readonly" style="width:200px" name="show" type="text"value="" />
<h1>系统时间设置</h1>
日期：<input id="date" name="date" type="text" onfocus="HS_setDate(this)"	value="" />
<br>
时间：<input id="hour" type="text" style="width:30px" value="" />:<input id="min" type="text" style="width:30px" value=""  />:<input id="sec" type="text" style="width:30px"  value="" />
<br>
<input type="button" vale="设置" onclick="return time_submit()" />
</body>
</html>