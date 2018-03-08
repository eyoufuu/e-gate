<?php 
	require_once('_inc.php');
	date_default_timezone_set('Asia/Shanghai');
	
	if(isset($_POST['date1']))
	{	
		session_start();
		$_SESSION["date"] = $_POST['date1'];
		$_SESSION["ips"] = $_POST['ips'];
		$_SESSION["ipe"] = $_POST['ipe'];
		
		if(isset($_POST['issearch']))
		{
			$_SESSION["issearch"]= $_POST['issearch'];
			$_SESSION["search"]= $_POST['search'];
		}
		else
		{
			unset($_SESSION["issearch"]);
			unset($_SESSION["search"]);	
		}
	}
?>	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<script language="javascript" type="text/javascript" src="../js/calenderJS.js" defer="defer"></script>
	<script language="javascript" type="text/javascript" src="./ipcheck.js"></script>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	
	<link href="../common/main.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="../common/common.js"></script>
</head>
<body>
<script type="text/javascript">
function submit_check()
{
	if(checkinput())
	{	
		return true;
	}
	else
	{
		return false;
	}			
}		
</script>
	<h1>审计条件</h1>
	<form name="queryinput" action="setting.php" method="post">
	<div class="bgFleet paddingAll"> 
	<table border="0" cellpadding="2" cellspacing="0"> 
			<tr>
				<td width ="90px"><span class="fontRed">*</span> 地址范围：</td>
				<td><input id="ips" name="ips" type="edit" autocomplete="on" value="<?php if(isset($_SESSION["ips"])){ echo $_SESSION["ips"];}else {echo "0.0.0.0";}?>" /></td>
				<td>-<input id="ipe" name="ipe" type="edit" value="<?php if(isset($_SESSION["ipe"])) {echo $_SESSION["ipe"];}else {echo "255.0.0.0";}?>" /></td>
			</tr>
			
			<tr>	
			   <td width="90px"><span class="fontRed">*</span> 查询日期：</td><td><input title="帮助：返回当前日期以后一个月的数据" id="date1" name="date1" type="edit" onfocus="HS_setDate(this)"
					value="<?php if(isset($_SESSION["date"])){echo $_SESSION["date"];}else{echo date('Y-m-d');}?>" />
			   </td>
			   
			</tr>
	</table>
    <table border="0" cellpadding="2" cellspacing="0">	
			<tr>			
			  <td width="90px" >关键字查询：</td>
			  <td width="20px"><input name="issearch" id="issearch" type="checkbox" value="2" <?php if(isset($_SESSION['issearch'])) echo "checked";?> onclick="onmark(this)" /></td>
			  <td><input id="search" name="search" type="edit" value="<?php  if(isset($_SESSION["search"])) echo $_SESSION["search"];?>" <?php if(!isset($_SESSION['issearch'])) echo 'disabled="true"';?>  /></td>
			</tr>			
		</table>		
		</div>
		<br>
		<input class = "inputButton_in" type="submit" style="margin-left:500px;" value="查询" onclick="return submit_check()" />
	</form>	
</body>
</html>