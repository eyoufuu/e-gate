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

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>用户管理</title>
<link href="../common/main.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../common/common.js"></script>
<script type="text/javascript">
function del_user()
{
	var answer = confirm("确实要删除选中的用户吗?");
	if(answer)
		return true;
	else
		return false;
}

function loginmode_change()
{
	document.form_mode.submit();
}

</script>
</head>

<body>

<div class="bodyTitle">
	<div class="bodyTitleLeft"></div>
  <div class="bodyTitleText">用户管理</div>
</div>

<style> 
.divcontent   {   border:solid   #369FD9   1px; overflow-y:   hidden;   height:   380px; width: 100%;}    
html
{ 
  overflow-y:scroll;
  overflow-x:hidden;
  width:1000px;
} 
</style>
<?php 
$sql_loginmode="select systemmode from globalpara;";
$arr_mode=$db->fetchRows($sql_loginmode);
$loginmode=$arr_mode[0]['systemmode'];
if($loginmode==0)
{
	$login_mode_ip="selected";
	$login_mode_account="";
}
else
{
	$login_mode_ip="";
	$login_mode_account="selected";
}
?> 
<form name="form_mode" id="form_mode" action="save_loginmode.php" method="post">
请选择用户登录模式：
<select   name="loginmode_select"   id="loginmode_select" onChange="loginmode_change()"> 
<option   value=0   <?php echo $login_mode_ip;?>>IP方式</option>
<option   value=1   <?php echo $login_mode_account;?>>账号登陆方式</option>
</select>
<br>
<font color=green>说明：账号方式指员工上网时如果其IP地址未在管理列表内，则需要输入账号密码，一般在动态分配IP地址的网络中使用；IP方式一般在使用静态分配IP地址的网络中使用，管理员可以通过IP地址来对应到相应的上网人员。</font>
<br><br>
<font color=green size=10>用户列表</font>

<?php 
$conn=mysql_connect("127.0.0.1","root","111111");
//设定每一页显示的记录数
$pagesize=15;
//mysql_select_db("baseconfig",$conn);
//取得记录总数$rs，计算总页数用
//$rs=mysql_query("select count(*) from useraccount",$conn);
$sql_coutuser="select count(*) as count from useraccount";
$rs = $db->fetchRows($sql_coutuser);
$numrows=$rs[0]['count'];
//计算总页数

$pages=intval($numrows/$pagesize);
if($numrows%$pagesize)
{
	$pages++;
}
//设置页数
if(isset($_GET['page']))
{
	$page=intval($_GET['page']);
}
else
{
	$page=1;
}
$offset=$pagesize*($page - 1);
?>
<div class="divcontent" >
<table width="100%" border="0" cellspacing="0" cellpadding="2">

  <tr>
    <td width="15%" align="center" class="bgFleet borderBottom">用户名称</td>
    <td width="20%" align="center" class="bgFleet borderBottom">登陆账号</td>
    <td width="15%" align="center" class="bgFleet borderBottom">用户IP地址</td>
    <td width="15%" align="center" class="bgFleet borderBottom">所属网段</td>
    <td width="20%" align="center" class="bgFleet borderBottom">分配策略</td>
    <td width="15%" align="center" class="bgFleet borderBottom">操作</td>
  </tr>
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

$sql = "select useraccount.*, netseg.name as dep_name, policy.name as policy_name from useraccount, 
		netseg, policy where useraccount.groupid=netseg.id and useraccount.policyid=policy.policyid order by useraccount.account_id limit $offset,$pagesize";
$arr = $db->fetchRows($sql);
foreach($arr as $value){
$color = getcolor();
if($value['bindip']=="0")
	$ip_bind="";
else
{
	$ip_bind=long2ip($value['bindip']);
}
if($value['specip']==0)
{
	$user_policy="黑名单策略";
}
else if($value['specip']==1)
{
	$user_policy="白名单策略";
}
else
{
	$user_policy=$value['policy_name'];
}
?> 
<tr > 
<td <?php echo $color ?> width = "15%" align="center"><?php echo $value['name'];?></td>
<td <?php echo $color ?> width = "20%" align="center"><?php echo $value['account'];?></td>
<td <?php echo $color ?> width = "15%" align="center"><?php echo $ip_bind;?></td>
<td <?php echo $color ?> width = "15%" align="center"><?php if($value['groupid']=="0") echo "未分配网段"; else echo $value['dep_name'];?></td>
<td <?php echo $color ?> width = "20%" align="center"><?php echo $user_policy;?></td>
<td <?php echo $color ?> width = "15%" align="center"><?php echo"<a href=user_set.php?id=".$value['account_id']."&new_user=0&randid=".rand().">编辑</a>";?>
|<?php echo"<a href=del_user.php?id=".$value['account_id']."&randid=".rand()." onclick=\"return del_user();\">删除</a>";?>
</td> 

</tr> 
<?php
}
unset($arr);
?>

</table>

</div>
<?php 
	$first=1;
	if($page-1>0)
		$prev=$page-1;
	else
		$prev=1;
	if($page+1<=$pages)
		$next=$page+1;
	else
		$next=$pages;
	$last=$pages;
	
	if($pages==0)
	{
		$first_web="disabled onclick=\"return false\"";
		$pre_web="disabled onclick=\"return false\"";
		$next_web="disabled onclick=\"return false\"";
		$last_web="disabled onclick=\"return false\"";
	}
	else 
	{
		if($page==1&&$page!=$pages)
		{
			$first_web="disabled onclick=\"return false\"";
			$pre_web="disabled onclick=\"return false\"";
			$next_web="";
			$last_web="";
		}
		
		else if($page!=1&&$page==$pages)
		{
			$first_web="";
			$pre_web="";
			$next_web="disabled onclick=\"return false\"";
			$last_web="disabled onclick=\"return false\"";
		}
		else if($page==1&&$page==$pages)
		{
			$first_web="disabled onclick=\"return false\"";
			$pre_web="disabled onclick=\"return false\"";
			$next_web="disabled onclick=\"return false\"";
			$last_web="disabled onclick=\"return false\"";
		}
		else
		{
			$first_web="";
			$pre_web="";
			$next_web="";
			$last_web="";
		}
	}
	echo "<div align=\"right\">";
	echo "<a href='user_manager.php?page=".$first."' $first_web><首页></a>";
	echo "&nbsp";
	echo "<a href='user_manager.php?page=".$prev."' $pre_web><上一页></a>";
	echo "&nbsp";
	echo $page;
	echo "&nbsp";
	echo "<a href='user_manager.php?page=".$next."' $next_web><下一页></a>";
	echo "&nbsp";
	echo "<a href='user_manager.php?page=".$last."' $last_web><尾页></a>";
	echo "<br>";
	echo "</div>"
?>

<a href=user_set.php?new_user=1&randid=<?php echo rand();?> style="font-size:20px;font-weight: bold;color:green;">添加用户信息</a>
</form>
</body>

</html>

