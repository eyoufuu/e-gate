<?php 
require_once ('_inc.php');
	$sql = "select * from smtpserver";
  	echo $sql;
 $arr = $db->fetchRow($sql); 
 $isopen = $arr['isopen'];
 $server = $arr['server'];
 $from = $arr['from'];
 $pwd = $arr['pwd'];
 $to = $arr['to'];
 echo $isopen .$server;
?>
<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link href="../common/main.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="../common/common.js"></script>
	</head>
	
	<body>
		<div class="bodyTitle">
		<div class="bodyTitleLeft"></div>
		<div class="bodyTitleText">邮件服务器设置</div>
		</div>
		
		<form name="queryinput" action="mailsetting.php" method="post">
			<div>启用邮件提醒：<input name="remind" id="remind" type="checkbox" value="1"  <?php if($isopen==1){echo 'checked="checked"';}?> onclick="onmark(this)" /></div> 
			<div>smtp服务器：    <input id="server" name="server" type="text" value="<?php echo $server; ?>" /></div> 
			<div>发送邮箱地址：<input id="from" name="from" type="text" value="<?php echo $from; ?>" /></div>
			<div>发送邮箱密码：<input id="pwd" name="pwd" type="password" value="<?php echo $pwd; ?>" /></div>
			<div>接收邮箱地址：<input id="to" name="to" type="text" value="<?php echo $to; ?>" /></div>
			<div><input type="submit" value="提交" onclick="return submit_check()" /></div>		
		</form>	
	</body>
</html>
<script>
onmark(document.getElementById("remind"));
function onmark(obj)
{
	var server = document.getElementById("server");
	var from = document.getElementById("from");
	var pwd = document.getElementById("pwd");
	var to =document.getElementById("to");
	server.disabled=!obj.checked;
	from.disabled=!obj.checked;
	pwd.disabled=!obj.checked;
	to.disabled=!obj.checked;
	if(obj.checked == true)
	{
		obj.value = 1;
	}
	else
	{
		objvalue =0;
	}
	
}
function submit_check()
{
	var server = document.getElementById("server").value;
	var from = document.getElementById("from").value;
	var pwd = document.getElementById("pwd").value;
	var to =document.getElementById("to").value;

	if(server=="" || from=="" ||pwd=="" || to=="")
	{
		alert("存在空值");
		return false;
	}
	return true;	
}
</script>
<?php 
/*require("mail.php"); 
########################################## 
$smtpserver = "smtp.126.com";//SMTP服务器 
$smtpserverport = 25;//SMTP服务器端口 
$smtpusermail = "qianbo0423@126.com";//SMTP服务器的用户邮箱 
$smtpemailto = "21192194@qq.com";//发送给谁 
$smtpuser = "qianbo0423";//SMTP服务器的用户帐号 
$smtppass = "770423";//SMTP服务器的用户密码 
$mailsubject = "中文";//邮件主题 
$mailbody = '';//邮件内容 
$mailtype = "HTML";//邮件格式（HTML/TXT）,TXT为文本邮件 
########################################## 
$smtp = new smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);//这里面的一个true是表示使用身份验证,否则不使用身份验证. 
$smtp->debug = TRUE;//是否显示发送的调试信息 
$smtp->sendmail($smtpemailto, $smtpusermail, $mailsubject, $mailbody, $mailtype);*/
?>