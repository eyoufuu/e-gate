<?php
function GetCurUrl()
{
	if(!empty($_SERVER["REQUEST_URI"]))
	{
		$scriptName = $_SERVER["REQUEST_URI"];
		$nowurl = $scriptName;
	}
	else
	{
		$scriptName = $_SERVER["PHP_SELF"];
		if(empty($_SERVER["QUERY_STRING"]))
		{
			$nowurl = $scriptName;
		}
		else
		{
			$nowurl = $scriptName."?".$_SERVER["QUERY_STRING"];
		}
	}
	return $nowurl;
} 
function getIP()
{ 
        if(getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) 
                $ip = getenv("HTTP_CLIENT_IP"); 
        elseif(getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) 
                $ip = getenv("HTTP_X_FORWARDED_FOR"); 
        elseif (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) 
                $ip = getenv("REMOTE_ADDR"); 
        elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) 
                $ip = $_SERVER['REMOTE_ADDR']; 
        else 
                $ip = "0.0.0.0"; 
        return $ip;
}   
   $dRootDir = '../';
     require_once($dRootDir . '_config.php');
   require_once($dRootDir . 'inc/params.php'); 
   require_once($dRootDir . 'inc/classes/db.php');
   require_once($dRootDir . 'inc/classes/template.php');
   require_once($dRootDir . 'inc/classes/util.php');
   
   if(isset($_POST['account']) && isset($_POST['password']))
   {
   		$db = new Db($gDb);
   		$sql = "select systemmode from globalpara";
   		$sysmode = $db->query2_count($sql,"M");
   		if($sysmode !=0)
   		{   			
	   		$sql = "select account,passwd,policyid,bindip from useraccount where account='".$_POST['account']."'";

	   		$result = $db->query2($sql,"M",false);
			
	   		if(count($result)==0)
	   			echo "<script>alert('用户名不存在')</script>";
	   			
	   		foreach($result as $row)
	   		{
		   		if(0 == strcmp($row['passwd'],$_POST['password']))
		   		{
		   			$usedip = ip2long(getip());
		   			$regip = $_POST['bindip'];
		   			if($usedip == $regip)
		   			{
		   				$cmd = "/home/sndcmd 12 %u %d 1";
		   				$cmd = sprintf($cmd,$usedip,$row['policyid']);
		   				echo $cmd;
		   				system($cmd);
		   			}
		   			else 
		   			{
		   				$aa = array();
		   				$cmd = "/usr/bin/sudo /home/sndcmd 12 %u 0 0";
		   				$cmd = sprintf($cmd,$row['bindip']);
		   				system($cmd);
		   				
		   				$cmd = "/usr/bin/sudo /home/sndcmd 12 %u %d 1";
		   				$cmd = sprintf($cmd,$usedip,$row['policyid']);
		   				system($cmd);
		   			}
		   			echo GetCurUrl();
		   			//header("Location:http://www.sohu.com");
		   		}
		   		else 
		   		{
		   			echo "<script>alert('密码不正确')</script>";
		   		}
		   		break;	 
	   		}
   		}
   		else
   		{
   			echo "<script>alert('无需登录')</script>";
   		}
   		
   }
   
 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link href="../common/main.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="../common/common.js"></script>
	<TITLE>上网登录</TITLE>
	<script>
	function loginCheck()
	{
		if(theForm.account.value == "")
		{
			alert("请输入用户名！");
			theForm.account.focus();
			return false;
		}
		else if(theForm.password.value == "")
		{
			alert("请输入密码！");
			theForm.password.focus();
			return false;
		}
		return true;
	}
	</script>
	
</head>
<body>
	<script>
document.write("<img src=../admin/company/pic/logo"+"?temp="+Date.parse(new Date())+" id='photo' width='150' height='75'/>" );
</script>
	<form name="theForm" method="post" action=<?php echo $_SERVER['SCRIPT_NAME']; ?>>
	  <table border="0"  cellspacing="0" cellpadding="0">	  	  
	  	  <tr><td><span class="fontRed">*</span>用户名：</td><td><input type="text" name="account" value="" /></td></tr>
	      <tr><td><span class="fontRed">*</span>密码：</td><td><input type="password" name="password" value="" /></td></tr>
	      <tr><td><input type="submit" value="登录" onclick = "return loginCheck()" /></td><td></td></tr>
      </table>
    </form>
</body>
</html>