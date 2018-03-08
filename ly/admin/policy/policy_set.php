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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<style>
html
{ 
  overflow-y:scroll;
  overflow-x:scroll;
  width:1000px;
}
.up{
border:1 outset royalblue;color:yellow;background:blue;cursor:hand
}
.down{
border:1 inset royalblue;color:#33ff33;background:blue;cursor:hand
}
.divweb_set{overflow-y:scroll; overflow-x:scroll; height:300px;}    
td.bsolid {
border-bottom-width: 1px;
border-bottom-style: solid;
}
input.no_edit {border:1px solid #fff;background:#fff;} 
input.edit_able {border:1px solid #000;background:#fff;width:150px;} 
p.audit{font-size:20px;font-family: "黑体"}
</style> 

<?php
function getcolor()
{
	static $colorvalue;
	if($colorvalue=="class='bgFleet'")
		$colorvalue="";
	else
		$colorvalue="class='bgFleet'";
	return($colorvalue);
}
?>

<?php 
$_SESSION["policy_id"] = $_GET["id"];
if($_GET["create"]==1)
	$_SESSION["create_policy"] = 1;
else 
	$_SESSION["create_policy"] = 0;
?>

<?php
$sql = "select * from policy where policyid=".$_SESSION["policy_id"].";";
//$audit_smtp = false;
//$audit_pop3 = false;
//$audit_post = false;
$arr = $db->fetchRows($sql);
foreach($arr as $value)
{
	$policy_name=$value['name'];
	$policy_description=$value['description'];
	if($value['smtpaudit']==1)
		$audit_smtp = "checked";
	if($value['pop3audit']==1)
		$audit_pop3 = "checked";
	if($value['postaudit']==1)
		$audit_post = "checked";
	if($value['webfilter']==1)
	{
		$audit_webtype = "checked";
	}
	else 
		$button_webtype = "disabled";
	if($value['filetypefilter']==1)
		$audit_filetype = "checked";
	else
		$button_filetype = "disabled";
	if($value['keywordfilter']==1)
		$audit_keyword = "checked";
	else
		$button_keyword = "disabled";
	if($value['time']==1)
	{
		$time_open = "checked";
		if($value['times1']=="0")
			$time_s1="00:00";
		else
		{
			if(strlen($value['times1'])<3)
			{
				if(strlen($value['times1'])<2)
					$time_s1="00:0".$value['times1'];
				else
					$time_s1="00:".$value['times1'];
			}
			else
			{
				if(strlen($value['times1'])==3)
					$time_s1="0".substr($value['times1'],0,1).":".substr($value['times1'],1,2);
				else if(strlen($value['times1'])==4)
					$time_s1=substr($value['times1'],0,2).":".substr($value['times1'],2,2);
				else 
					$time_s1="00:00";
			}
		}
			
		if($value['times2']=="0")
			$time_s2="00:00";
		else
		{
			if(strlen($value['times2'])<3)
			{
				if(strlen($value['times2'])<2)
					$time_s2="00:0".$value['times2'];
				else
					$time_s2="00:".$value['times2'];
			}
			else
			{
				if(strlen($value['times2'])==3)
					$time_s2="0".substr($value['times2'],0,1).":".substr($value['times2'],1,2);
				else if(strlen($value['times2'])==4)
					$time_s2=substr($value['times2'],0,2).":".substr($value['times2'],2,2);
				else 
					$time_s2="00:00";
			}
		}
		if($value['timee1']=="0")
			$time_e1="24:00";
		else
		{
			if(strlen($value['timee1'])<3)
			{
				if(strlen($value['timee1'])<2)
					$time_e1="00:0".$value['timee1'];
				else
					$time_e1="00:".$value['timee1'];
			}
			else
			{
				if(strlen($value['timee1'])==3)
					$time_e1="0".substr($value['timee1'],0,1).":".substr($value['timee1'],1,2);
				else if(strlen($value['timee1'])==4)
					$time_e1=substr($value['timee1'],0,2).":".substr($value['timee1'],2,2);
				else 
					$time_e1="24:00";
			}
		}
		if($value['timee2']=="0")
			$time_e2="24:00";
		else
		{
			if(strlen($value['timee2'])<3)
			{
				if(strlen($value['timee2'])<2)
					$time_e2="00:0".$value['timee2'];
				else
					$time_e2="00:".$value['timee2'];
			}
			else
			{
				if(strlen($value['timee2'])==3)
					$time_e2="0".substr($value['timee2'],0,1).":".substr($value['timee2'],1,2);
				else if(strlen($value['timee2'])==4)
					$time_e2=substr($value['timee2'],0,2).":".substr($value['timee2'],2,2);
				else 
					$time_e2="24:00";
			}
		}
		$week_set=$value['week'];
		if(intval($week_set)&1)
			$week_monday="checked";
		if(intval($week_set)&2)
			$week_tuesday="checked";
		if(intval($week_set)&4)
			$week_wednesday="checked";
		if(intval($week_set)&8)
			$week_thursday="checked";
		if(intval($week_set)&16)
			$week_friday="checked";
		if(intval($week_set)&32)
			$week_saturday="checked";
		if(intval($week_set)&64)
			$week_sunday="checked";
	}
	else
	{
		$time_enable = "disabled";
		$time_readonly="readOnly";
		$time_s1="00:00";
		$time_s2="00:00";
		$time_e1="24:00";
		$time_e2="24:00";
	}
}
?> 

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>策略管理</title>
<link href="../common/main.css" rel="stylesheet" type="text/css"/>
<link href="../style/tab.css" rel="stylesheet" type="text/css"/>
<link rel="STYLESHEET" type="text/css" href="../js/tree/dhtmlxtree.css">
<script  src="../js/tree/dhtmlxcommon.js"></script>
<script  src="../js/tree/dhtmlxtree.js"></script>
<script type="text/javascript" src="../common/common.js"></script>
<script type="text/javascript" src="../js/time_input.js"></script>
<script type="text/javascript">

<?php 
$sql_count_keyword = "select count(*) as count_keyword from keywordinfo where policyid=".$_GET["id"];
//$arr_keywordinfo = $db->fetchRows($sql_count_keyword);
$keyword_count = $db->query2_count($sql_count_keyword);
//$keyword_count=$arr_keywordinfo[0]['count_keyword'];
?>
var pro_set_first=0; //如果是第一次点击协议控制的按钮，则要从数据库中读取控制状态
var new_keyword_id=parseInt(<?php echo $keyword_count;?>); //全局变量，用来区分新增关键字时的关键字ID

/*
 * 控制大项的显示，主要为网页过滤、审计设置、协议设置、时间设置的div显示与隐藏
 */
function show_div(tab_id,a)
{
	var tab = document.getElementById('tabs').getElementsByTagName('div');
	for(var j=0;j<tab.length;j++)
	{
		tab[j].className='goodsDetailTab';
	}
	document.getElementById(tab_id).className='goodsDetailTab active';
	var demo = document.getElementById("policy_set");
	var divArray = demo.getElementsByTagName("div");
	for (var i=0;i<divArray.length;i++) 
	{
		if (divArray[i].id == a) 
		{
			divArray[i].style.display='';
			if(a=="pro_set"&&pro_set_first==0)
			{
				<?php 
				$sql_proinfo="select proctl from policy where policyid=".$_GET["id"];
				$arr_proinfo = $db->fetchRows($sql_proinfo);
				$pro_set=$arr_proinfo[0]['proctl'];
				for($i=1;$i<strlen($pro_set);$i++)
				{
					if($pro_set[$i]=="0")
					{
				?>
						var pro_id="<?=$i?>";
						tree2.setCheck(pro_id,true);
				<?php 
					}	
				}
				unset($arr_proinfo);
				?>
				pro_set_first=1;
			}
		}
		else 
		{
			if(divArray[i].id == "time_set_mini"&&a=="time_set")
				divArray[i].style.display='';
			else if(divArray[i].id == "treeboxbox_tree2"&&a=="pro_set")
				divArray[i].style.display='block';
			else if(divArray[i].id == ""&&a=="pro_set")
				divArray[i].style.display='block';
			else
			{
				divArray[i].style.display='none';
			}
		}
	}
}

/*
 * 提交表单时检查表单数据是否符合规则
 */
function check_input()
{
	var policyname=document.getElementById("policy_name");
	if(policyname.value == "")
	{
		alert("策略名称不允许为空，请重新输入！");
		return false;
	}
	var policydescription=document.getElementById("policy_description");
	if(policydescription.value == "")
	{
		alert("策略描述不允许为空，请重新输入！");
		return false;
	}

	if(document.getElementById("time_open").checked == true)
	{
		var times1_value=document.getElementById("time_start1").value;
		var timee1_value=document.getElementById("time_end1").value;
		var times2_value=document.getElementById("time_start2").value;
		var timee2_value=document.getElementById("time_end2").value;
		if(times1_value.split(":")[0]==""||times1_value.split(":")[1]==""||timee1_value.split(":")[0]==""||timee1_value.split(":")[1]==""||times2_value.split(":")[0]==""||times2_value.split(":")[1]==""||timee2_value.split(":")[0]==""||timee2_value.split(":")[1]=="")
		{
			alert("您输入的时间格式有误，请重新输入！");
			return false;
		}
		var times1_hours=parseInt(times1_value.split(":")[0]);
		var times1_second=parseInt(times1_value.split(":")[1]);
		var timee1_hours=parseInt(timee1_value.split(":")[0]);
		var timee1_second=parseInt(timee1_value.split(":")[1]);
	
		var times2_hours=parseInt(times2_value.split(":")[0]);
		var times2_second=parseInt(times2_value.split(":")[1]);
		var timee2_hours=parseInt(timee2_value.split(":")[0]);
		var timee2_second=parseInt(timee2_value.split(":")[1]);
		if(times1_hours>timee1_hours||times2_hours>timee2_hours)
		{
			alert("策略结束时间必须大于开始时间，请重新输入！");
			return false;
		}
		if(times1_hours==timee1_hours||times2_hours==timee2_hours)
		{
			if(times1_second>timee1_second||times2_second>timee2_second)
			{
				alert("策略结束时间必须大于开始时间，请重新输入！");
				return false;
			}
		}
	}
	document.getElementById("pro_select").value=tree2.getAllChecked();
/*	document.getElementById("time_start1").value=times1_hours.toString()+times1_second.toString();
	document.getElementById("time_end1").value=timee1_hours.toString()+timee1_second.toString();
	document.getElementById("time_start2").value=times2_hours.toString()+times2_second.toString();
	document.getElementById("time_end2").value=timee2_hours.toString()+timee2_second.toString();*/
	return true;
}

/*
 * 增加新的关键字
 */
function add_keyword_table_Row() 
{ 
if(new_keyword_id>49)
{
	alert("每条策略最多允许设置50个关键字，您已经设置了50个关键字，您可以通过修改现有的关键字来达到您的需求。");
	return false;
}
var rowid;
if(new_keyword_id < 10)
{
	rowid = "000"+String(new_keyword_id);
}
else
	rowid = "00"+String(new_keyword_id);
new_keyword_id = new_keyword_id+1;
var TemO=document.getElementById("keywordtable"); 
var newInput = document.createElement("input"); 
newInput.type = "hidden";
newInput.name = "hidden_data[]";
newInput.id = rowid;
newInput.value="0";
TemO.appendChild(newInput); 
//添加一行 
var newTr = keywordtable.insertRow(-1); 
//添加两列 
var newTd1 = newTr.insertCell(-1); 
var newTd2 = newTr.insertCell(-1); 
var newTd3 = newTr.insertCell(-1);
var newTd4 = newTr.insertCell(-1);
var newTd5 = newTr.insertCell(-1);
newTd1.style.cssText="text-align:left"; 
newTd2.style.cssText="text-align:center"; 
newTd3.style.cssText="text-align:center"; 
newTd4.style.cssText="text-align:left"; 
newTd5.style.cssText="text-align:center"; 
//设置列内容和属性 
newTd1.innerHTML = "<input class=\"edit_able\" id="+rowid+"_1 value=\"\" onkeyup=\"input_limit(this, 20)\" onclick=\"enable_edit(this.id)\" onchange=\"update_heddeninput(this.id)\" onblur=\"disable_edit(this.id)\">";
newTd2.innerHTML = "<input type=\"checkbox\" align=\"center\" id="+rowid+"_2 value=1 onclick=\"update_heddeninput(this.id)\">";
newTd3.innerHTML = "<input type=\"checkbox\" id="+rowid+"_3 value=1 onclick=\"update_heddeninput(this.id)\">";
newTd4.innerHTML = "<input type=\"text\" class=\"edit_able\" id="+rowid+"_4 value=\"\"  onkeyup=\"input_limit(this, 40)\" onMouseOver=\"show(this);\" onMouseOut=\"hide(this);\" onclick=\"enable_edit(this.id)\" onchange=\"update_heddeninput(this.id)\" onblur=\"disable_edit(this.id)\">";
newTd5.innerHTML = "<input type=\"button\" id="+rowid+"_5 value=\"删除\" onclick=\"del_keyword(this.id, this.parentNode.parentNode.rowIndex)\">"; 
}   

/*
 * 删除关键字
 */
function del_keyword(id, row_index)
{
	var hiddenid = id.split("_")[0];
	var hidden_obj = document.getElementById(hiddenid);
	hidden_obj.value="";
	document.getElementById("keywordtable").deleteRow(row_index);
	if(id.substr(0,1)!="0")
	{
		var deldata = document.getElementById("del_id").value;
		if(""==deldata)
			deldata = deldata+id.split("_")[0];
		else
			deldata = deldata+"@"+id.split("_")[0];
		document.getElementById("del_id").value = deldata;
	}
}

/*
 * 使关键字的编辑框为可输入状态
 */
function enable_edit(id)
{
	var input_id = document.getElementById(id);
	input_id.readOnly = false; 
	input_id.className = "edit_able";
}

/*
 * 用来更新隐藏域的信息，该隐藏域用来保存关键字修改后的信息，统一提交
 */
function update_heddeninput(id)
{
	if(""==document.getElementById(id).value)
	{
		return false;
	}
	var hiddenid = id.split("_")[0];
	var hidden_obj = document.getElementById(hiddenid);
	var name_id = id.split("_")[0] + "_1";
	var pass_id = id.split("_")[0] + "_2";
	var log_id = id.split("_")[0] + "_3";
	var description_id = id.split("_")[0] + "_4";
	var name_value = document.getElementById(name_id).value;
	if(""==name_value)
		return false;
	var pass_value=(document.getElementById(pass_id).checked == true)?0:1;
	var log_value=(document.getElementById(log_id).checked == true)?1:0;
	var description_value = document.getElementById(description_id).value;
//	if(""==description_value)
//		return false;
	hidden_obj.value=hiddenid+"#"+name_value+"#"+pass_value+"#"+log_value+"#"+description_value;
}

/*
 * 使关键字的编辑框变为只读状态
 */
function disable_edit(id)
{
	var input_id = document.getElementById(id);
	if(input_id.value == "")
	{
		if(id.split("_")[1] == "1")
		{
			alert("关键字不能为空，请输入需要添加的关键字");
			document.getElementById(id).focus();
			return false;
		}
	}
	input_id.readOnly = true; 
	input_id.className = "no_edit";
}

/*
 * 时间设置的DIV显示或者隐藏控制
 */
function time_control()
{
	var time_control_checkbox = document.getElementById("time_open");
	var time_control_div = document.getElementById("time_set_mini");
	if(time_control_checkbox.checked == true)
	{
		time_control_div.disabled=false;
		document.getElementById("time_start1").readOnly=false;
		document.getElementById("time_start2").readOnly=false;
		document.getElementById("time_end1").readOnly=false;
		document.getElementById("time_end2").readOnly=false;
	}
	else
	{
		time_control_div.disabled=true;
		document.getElementById("time_start1").readOnly=true;
		document.getElementById("time_start2").readOnly=true;
		document.getElementById("time_end1").readOnly=true;
		document.getElementById("time_end2").readOnly=true;
	}
}

/*
 * 用来控制网页网页过滤中三个子项的显示与隐藏
 */
function web_control(id)
{
	if(id == "audit_webtype")
	{
		if(document.getElementById("audit_webtype").checked==true)
			document.getElementById("web_select").disabled=false;
		else
		{
			document.getElementById("web_select").disabled=true;
			document.getElementById("div_webtype").style.display='none';
		}
	}
	if(id == "audit_filetype")
	{
		if(document.getElementById("audit_filetype").checked==true)
			document.getElementById("file_select").disabled=false;
		else
		{
			document.getElementById("file_select").disabled=true;
			document.getElementById("div_filetype").style.display='none';
		}
	}
	if(id == "audit_keywordtype")
	{
		if(document.getElementById("audit_keywordtype").checked==true)
		{
			document.getElementById("keyword_select").disabled=false;
		}
		else
		{
			document.getElementById("keyword_select").disabled=true;
			document.getElementById("div_keyword").style.display='none';
		}
	}
}

function input_limit(obj, length)
{
	if(obj.value.length>length)
	{
		obj.value = obj.value.substring(0,length); 
	}
}

//取X轴位置
function mouseX(evt) {
    // firefox
    if (evt.pageX) return evt.pageX;
    // IE
    else if (evt.clientX)
        return evt.clientX + (document.documentElement.scrollLeft ? document.documentElement.scrollLeft : document.body.scrollLeft);
    else return null;
}
// 取Y轴位置
function mouseY(evt) {
    // firefox
    if (evt.pageY) return evt.pageY;
    // IE
    else if (evt.clientY)
        return evt.clientY + (document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop);
    else return null;
}


function   show(obj)
{   
	document.all.mydiv.style.display="block";   
//	obj.style.background="#FF9933   ";
	document.all.mydiv.style.left=mouseX(event);   
	document.all.mydiv.style.top=mouseY(event)+5;   
	document.all.mydiv.innerText=obj.value;     
	document.all.mydiv.style.background="#DD9933   ";
}   
function   hide(obj)
{   
	mydiv.style.display="none"   
//	obj.style.background="#FFFFFF";   
} 

function cancel_edit()
{
	var answer = confirm("您确认要取消编辑吗？");
	if(answer)
		window.location.href="policy_name.php";
}

</script>
</head>

<body>
<div class="bodyTitle">
	<div class="bodyTitleLeft"></div>
  <div class="bodyTitleText">策略管理</div>
</div>
<br>

<form name="form_1" id="form_1" action="save_policy.php" method="post">
<table align="center"  width=90% cellspacing=0 cellpadding=0 style="border:solid   000000   1px;">
<tr>
<td>
	<br>
	&nbsp&nbsp策略名称：
	<input type="text" id="policy_name" style="border-width:0;border-bottom:1px   solid   blue" name="policyname" <?php if($_GET['id']=="0") echo "readonly";?> value="<?php if($_GET["create"]!=1) echo $policy_name;?>" onkeyup="input_limit(this, 10)">
	<br>
	<br>
	<div style="width:800px">
	&nbsp&nbsp策略描述：
	<input type="text" id="policy_description" size="100" style="border-width:0;border-bottom:1px   solid   blue" name="description" <?php if($_GET['id']=="0") echo "readonly";?> value="<?php if($_GET["create"]!=1) echo $policy_description;?>" onkeyup="input_limit(this, 50)">
	</div>
	<br>
	
	<div class="goods-detail-tab clearfix" id="tabs">
	<div class="goodsDetailTab active" id="tab_time" onclick="show_div(this.id, 'time_set')">
    	<span>时间设置</span>
    </div>
	<div class="goodsDetailTab" id="tab_pro" onclick="show_div(this.id, 'pro_set')">
    	<span>协议控制</span>
    </div>
	<div class="goodsDetailTab" id="tab_web" onclick="show_div(this.id, 'web_set')">
    	<span>网页过滤</span>
    </div>
	<div class="goodsDetailTab" id="tab_audit" onclick="show_div(this.id,'audit_set')">
    	<span>审计设置</span>
    </div>
</div>
<div class="clear"></div>
	<div id="policy_set" style="width:100%;   height:auto !important; height:500px; min-height:500px; overflow-x:hidden;">
	<div id="time_set" style="display:block;">
	<br>
	&nbsp&nbsp是否开启时间限制：
	<input type="checkbox" name="time_open" id="time_open" value=1 <?php echo $time_open;?> onclick="time_control()">
	<div id="time_set_mini"  <?php echo $time_enable;?>>
	<br>
	&nbsp&nbsp您希望本策略在一天中的什么时间执行：
	
	<table  width="90%"  cellspacing=0 cellpadding=15 style="border:solid   000000   1px;">
	<tr>
	<td >
		开始时间1：
		<input id="time_start1" name="time_start1"  onkeyDown="validate(this,2)" value=<?php echo $time_s1;?> <?php echo $time_readonly;?>>
		<br>
		结束时间1：
		<input id="time_end1" name="time_end1" onkeyDown="validate(this,2)" value=<?php echo $time_e1;?> <?php echo $time_readonly;?>>
	</td>
	</tr>
	<tr>
	<td >
		开始时间2：
		<input id="time_start2" name="time_start2" onkeyDown="validate(this,2)" value=<?php echo $time_s2;?> <?php echo $time_readonly;?>>
		<br>
		结束时间2：
		<input id="time_end2" name="time_end2" onkeyDown="validate(this,2)" value=<?php echo $time_e2;?> <?php echo $time_readonly;?>>
	</td>
	</tr>
	</table>
	<br>
	&nbsp&nbsp您希望本策略在一周中的星期几执行：
	<br>
	<table  width="90%" cellspacing=0 cellpadding=15 style="border:solid   000000   1px;">
	<tr>
	<td >
		<input type="checkbox" name="week_set[]" value=1 <?php echo $week_monday?>>
		星期一
		<br><input type="checkbox" name="week_set[]" value=2 <?php echo $week_tuesday?>>
		星期二
		<br><input type="checkbox" name="week_set[]" value=4 <?php echo $week_wednesday?>>
		星期三
		<br><input type="checkbox" name="week_set[]" value=8 <?php echo $week_thursday?>>
		星期四
		<br><input type="checkbox" name="week_set[]" value=16 <?php echo $week_friday?>>
		星期五
		<br><input type="checkbox" name="week_set[]" value=32 <?php echo $week_saturday?>>
		星期六
		<br><input type="checkbox" name="week_set[]" value=64 <?php echo $week_sunday?>>
		星期天
		<br>
	</td>
	</tr>
	</table>
	</div>
	</div>
	<div id="web_set" style="display:none; overflow-x:hidden;">
	<br>
	<table  width="100%" cellpadding=15 style="border:solid 1px;">
	<tr>
	<td class="bgFleet borderBottom">
		管理列表
	</td>
	</tr>
	<tr>
	<td>
	网站分类
	<br>
	&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
	<input type="button" name="web_select"  id="web_select" <?php echo $button_webtype;?> value="管理列表" onclick="document.getElementById('div_webtype').style.display=='none'?document.getElementById('div_webtype').style.display='block':document.getElementById('div_webtype').style.display='none'">
	&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
	&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
	&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
	&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
	<input type="checkbox" name="audit[]" id="audit_webtype" value=4 <?php echo $audit_webtype;?> onclick="web_control(this.id)">
	<div id="div_webtype" class="divweb_set" style="display:none; width:90%; overflow-x:hidden;">
	<br>
	<table  width="100%" cellspacing="0" cellpadding=2 style="border:solid 1px;">
	<tr>
    <td align="center" width=80px class="bgFleet borderBottom">网站分类</td>
    <td align="center" width=80px class="bgFleet borderBottom">是否阻挡</td>
    <td align="center" width=80px class="bgFleet borderBottom">日志记录</td>
    <td align="center" class="bgFleet borderBottom">描述</td>
  	</tr>
  	<?php 
  	$sql_webcat = "select * from webcat";
	$arr_webcat = $db->fetchRows($sql_webcat);
	foreach($arr_webcat as $webcat_value)
	{
		$sql_webinfo = "select pass, log from webinfo where policyid=".$_GET["id"]." and webid=".$webcat_value['webid'];
		$arr_webinfo = $db->fetchRows($sql_webinfo);
		foreach($arr_webinfo as $webinfo_value)
		{
			$webinfo_pass = "";
			$webinfo_log = "";
			if($webinfo_value['pass']==0)
				$webinfo_pass = "checked";
			if($webinfo_value['log']==1)
				$webinfo_log = "checked";
	?>
		<tr>
	    <td align="center" width=80px ><?php echo $webcat_value['name'];?></td>
	    <td align="center" width=80px >
	    <input type="checkbox" name="webpass_set[]"  value=<?php echo $webcat_value['webid'];?> <?php echo $webinfo_pass;?>>
	    </td>
	    <td align="center" width=80px >
	    <input type="checkbox" name="weblog_set[]"  value=<?php echo $webcat_value['webid'];?> <?php echo $webinfo_log;?>>
	    </td>
	    <td align="center" ><?php echo $webcat_value['description'];?></td>
	  	</tr>
	<?php
		}
	}

	?>
	</table>
	</div> 
	</td>
	</tr>
	<tr>
	<td>
	文件类型
	<br>
	&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
	<input type="button" name="file_select"  id="file_select" <?php echo $button_filetype;?> value="管理列表" onclick="document.getElementById('div_filetype').style.display=='none'?document.getElementById('div_filetype').style.display='block':document.getElementById('div_filetype').style.display='none'">
	&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
	&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
	&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
	&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
	<input type="checkbox" name="audit[]" id="audit_filetype" value=5 <?php echo $audit_filetype;?> onclick="web_control(this.id)">
	<div id="div_filetype" class="divweb_set" style="display:none; width:90%; overflow-x:hidden;">
		<br>
	<table  width="100%" cellspacing="0" cellpadding=2 style="border:solid 1px;">
	<tr>
    <td align="center" width=80px class="bgFleet borderBottom">文件类型</td>
    <td align="center" width=80px class="bgFleet borderBottom">是否阻挡</td>
    <td align="center" width=80px class="bgFleet borderBottom">日志记录</td>
    <td align="center" class="bgFleet borderBottom">描述</td>
  	</tr>
  	<?php 
  	$sql_filecat = "select * from filecat";
	$arr_filecat = $db->fetchRows($sql_filecat);
	foreach($arr_filecat as $filecat_value)
	{
		$sql_filebinfo = "select pass, log from fileinfo where policyid=".$_GET["id"]." and fileid=".$filecat_value['typeid'];
		$arr_fileinfo = $db->fetchRows($sql_filebinfo);
		foreach($arr_fileinfo as $fileinfo_value)
		{
			$fileinfo_pass = "";
			$fileinfo_log = "";
			if($fileinfo_value['pass']==0)
				$fileinfo_pass = "checked";
			if($fileinfo_value['log']==1)
				$fileinfo_log = "checked";
	?>
		<tr >
	    <td align="center" width=80px ><?php echo $filecat_value['name'];?></td>
	    <td align="center" width=80px >
	    <input type="checkbox" name="filepass_set[]"  value=<?php echo $filecat_value['typeid'];?> <?php echo $fileinfo_pass;?>>
	    </td>
	    <td align="center" width=80px >
	    <input type="checkbox" name="filelog_set[]"  value=<?php echo $filecat_value['typeid'];?> <?php echo $fileinfo_log;?>>
	    </td>
	    <td align="center" ><?php echo $filecat_value['description'];?></td>
	  	</tr>
	<?php
		}
	}

	?>
	</table>
	</div> 
	</td>
	</tr>
	<tr>
	<td>
	关键字
	<br>
	&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
	<input type="button" name="keyword_select" id="keyword_select" <?php echo $button_keyword;?> value="管理列表" onclick="document.getElementById('div_keyword').style.display=='none'?document.getElementById('div_keyword').style.display='block':document.getElementById('div_keyword').style.display='none'">
	&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
	&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
	&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
	&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
	<input type="checkbox" name="audit[]" id="audit_keywordtype" value=6 <?php echo $audit_keyword;?> onclick="web_control(this.id)">
	<div id="div_keyword" class="divweb_set"  style="display:none; width:90%; overflow-x:hidden;">
	<input type="hidden" name="del_id" id="del_id" value="">
	<table  id="keywordtable" width="100%" cellspacing="0" cellpadding=2 style="border:solid 1px;">
	<tr>
	<td class="bsolid">
	<input type="button" name="keyword_add"  value="添加" onclick="add_keyword_table_Row();">
	</td>
	</tr>
	<tr>
    <td align="left" width="15%" class="bgFleet borderBottom">关键字</td>
    <td align="center" width="15%" class="bgFleet borderBottom">是否阻挡</td>
    <td align="center" width="15%" class="bgFleet borderBottom">日志记录</td>
    <td align="left" width="40%" class="bgFleet borderBottom">描述</td>
    <td align="center" width="15%" class="bgFleet borderBottom">操作</td>
  	</tr>
  	<?php 
  	$sql_keywordinfo = "select * from keywordinfo where policyid=".$_GET["id"];
	$arr_keywordinfo = $db->fetchRows($sql_keywordinfo);
	foreach($arr_keywordinfo as $keyword_value)
	{
		$keywordinfo_pass = "";
		$keywordinfo_log = "";
		if($keyword_value['pass']==0)
			$keywordinfo_pass = "checked";
		if($keyword_value['log']==1)
			$keywordinfo_log = "checked";
	?>
		<tr >
		<input type="hidden" name="hidden_data[]" id=<?php echo $keyword_value['keywordid'];?> value="0">
	    <td align="left" width="15%" >
	    <input class="no_edit" readOnly="true" id=<?php echo $keyword_value['keywordid']."_1";?> value=<?php echo $keyword_value['utf'];?>  onkeyup="input_limit(this, 20)" onclick="enable_edit(this.id)" onchange="update_heddeninput(this.id)" onblur="disable_edit(this.id)">
		</td>
	    <td align="center" width="15%" >
	    <input type="checkbox" name="keywordpass_set[]"  id=<?php echo $keyword_value['keywordid']."_2";?> onclick="update_heddeninput(this.id)" value=1 <?php echo $keywordinfo_pass;?>>
	    </td>
	    <td align="center" width="15%" >
	    <input type="checkbox" name="keywordlog_set[]"  id=<?php echo $keyword_value['keywordid']."_3";?> onclick="update_heddeninput(this.id)" value=1 <?php echo $keywordinfo_log;?>>
	    </td>
	    <td align="left" width="40%">
	    <input class="no_edit" readOnly="true" id=<?php echo $keyword_value['keywordid']."_4";?> value="<?php echo $keyword_value['description'];?>" onMouseOver="show(this);" onMouseOut="hide(this);" onkeyup="input_limit(this, 40)" onclick="enable_edit(this.id)" onchange="update_heddeninput(this.id)" onblur="disable_edit(this.id)">
	    <td align="center"  width="15%">
	    <input type="button" id=<?php echo $keyword_value['keywordid']."_5";?> value="删除" onclick="del_keyword(this.id, this.parentNode.parentNode.rowIndex)">
	    </td>
	  	</tr>
	<?php
	}

	?>
	</table>
	</div> 
	</td>
	</tr>
	</table>
	</div>
	<div id="pro_set" style="display:none;">
	<table style="margin-left:20px; margin-top:20px">
    <tr>
    	<input type="hidden" name="pro_select" id="pro_select" value="0">
        <td valign="top">
            <div id="treeboxbox_tree2" style="width:250px; height:450px;background-color:#f5f5f5;border :1px solid Silver;; overflow:auto;"></div>
        </td>
    </tr>
	</table>  
	<script>
	tree2 = new dhtmlXTreeObject("treeboxbox_tree2", "100%", "100%", '0');
	tree2.setSkin('dhx_skyblue');
	tree2.setImagePath("../js/tree/imgs/csh_bluebooks/");
	tree2.enableCheckBoxes(1);
	tree2.enableThreeStateCheckboxes(true);
	tree2.loadXML("../db/protocol_data.xml");
	</script>
	</div>
	<div id="audit_set" style="display:none;">
	<br>
	&nbsp&nbsp
	请在需要审计的项目后面打钩：
	<br>
	<br>
	<br>
	<br>
	<p class="audit">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp客户端发送邮件：&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
	<input type="checkbox" name="audit[]" id="audit_smtp" value=1 <?php echo $audit_smtp;?>></p>
	<br>
	<p class="audit">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp客户端接收邮件：&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
	<input type="checkbox" name="audit[]" id="audit_pop3" value=2 <?php echo $audit_pop3;?>></p>
	<br>
	<p class="audit">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp网页发送邮件及发帖审计:&nbsp
	<input type="checkbox" name="audit[]" id="audit_post" value=3 <?php echo $audit_post;?>></p>
	<br>
	</div>
	</div>
</td>
</tr>
</table>
<input type="submit" name="Submit3" style="width:70px;height:30px;float:left; margin-left:300px;" value="提交" onclick="return check_input();">
<input type="button" style="width:70px;height:30px; "  name="cancel" value="取消" onclick="cancel_edit();">
</form>
<div   id="mydiv"   style="position:absolute;display:none">   
</div>
</body>
</html>