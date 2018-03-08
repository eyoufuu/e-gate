<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理</title>
<link href="../common/main.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../common/common.js"></script>
</head>

<body>
<div class="bodyTitle">
	<div class="bodyTitleLeft"></div>
  <div class="bodyTitleText">用户列表</div>
</div>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td width="30" class="bgFleet borderBottom">编号</td>
    <td class="bgFleet borderBottom">用户名</td>
    <td class="bgFleet borderBottom">姓名</td>
    <td class="bgFleet borderBottom">单位名称</td>
    <td class="bgFleet borderBottom">联系电话</td>
    <td align="center" class="bgFleet borderBottom">注册时间</td>
    <td align="center" class="bgFleet borderBottom">最后登录</td>
    <td align="center" class="bgFleet borderBottom">登录次数</td>
    <td align="center" class="bgFleet borderBottom">操作</td>
  </tr>
 {--mc_getlist type="user" items="f_id, f_userName, f_name, f_company, f_tel, f_regTime, f_loginTime, f_loginTimes" page="$page" pagesize="20" varname="arr"--}
 {--section name=loop loop=$arr--}
 {--if $smarty.section.loop.index%2==1--}
 {--assign var=bgClass value="class='bgFleet'"--}
 {--else--}
 {--assign var=bgClass value=""--}
 {--/if--}
  <tr>
    <td align="center" {--$bgClass--}>{--$arr[loop].f_id--}</td>
    <td {--$bgClass--}><a href="user.php?uId={--$arr[loop].f_id--}&p={--$page--}">{--$arr[loop].f_userName--}</a></td>
    <td {--$bgClass--}>{--$arr[loop].f_name--}</td>
    <td {--$bgClass--}>{--$arr[loop].f_company--}</td>
    <td {--$bgClass--}>{--$arr[loop].f_tel--}</td>
    <td align="center" {--$bgClass--}>{--$arr[loop].f_regTime|date_format:'%Y-%m-%d'--}</td>
    <td align="center" {--$bgClass--}>{--if $arr[loop].f_loginTime--}{--$arr[loop].f_loginTime|date_format:'%Y-%m-%d'--}{--else--}-{--/if--}</td>
    <td align="center" {--$bgClass--}>{--$arr[loop].f_loginTimes--}</td>
    <td align="center" {--$bgClass--}><a href="user.php?uId={--$arr[loop].f_id--}&p={--$page--}">详情</a> | <a href="delete.php?uId={--$arr[loop].f_id--}&p={--$page--}"" onClick="return confirm('确实要删除这个用户吗？');">删除</a></td>
  </tr>
  {--/section--}
</table>
<br>
<div>
{--mc_getpagecount type="user" pagesize="20" varname="pagecount"--}
{--paginator page="$page" pagecount="$pagecount" url="?p=PAGENUMBER"--}
</div>
</body>
</html>
