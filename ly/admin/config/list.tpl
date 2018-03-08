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
  <div class="bodyTitleText">网络接口卡列表</div>
</div>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td width="30" class="bgFleet borderBottom">编号</td>
    <td class="bgFleet borderBottom">名称</td>
	<td align="center" class="bgFleet borderBottom">类型</td>
    <td align="center" class="bgFleet borderBottom">模式</td>
    <td align="center" class="bgFleet borderBottom">连接</td>
    <td align="center" class="bgFleet borderBottom">带宽</td>
    <td align="center" class="bgFleet borderBottom">ip地址</td>
    <td align="center" class="bgFleet borderBottom">mac地址</td>
    <td align="center" class="bgFleet borderBottom">子网掩码</td>
    <td align="center" class="bgFleet borderBottom">操作</td>
  </tr>
  {--mc_getlist type="ly_config_interface" items="ifid, name, type, mode, link, bandwidth, ip, mac, netmask " varname="arr"--}
  {--section name=loop loop=$arr--}
 {--if $smarty.section.loop.index%2==1--}
 {--assign var=bgClass value="class='bgFleet'"--}
 {--else--}
 {--assign var=bgClass value=""--}
 {--/if--}
  <tr>
    <td align="center" {--$bgClass--}>{--$arr[loop].ifid--}</td>
    <td {--$bgClass--}>{--$arr[loop].name--}</td>
    <td align="center" {--$bgClass--}>{--$arr[loop].type--}</td>
    <td align="center" {--$bgClass--}>{--$arr[loop].mode--}</td>
    <td align="center" {--$bgClass--}>{--$arr[loop].link--}</td>
    <td align="center" {--$bgClass--}>{--$arr[loop].bandwidth--}</td>
    <td align="center" {--$bgClass--}>{--$arr[loop].ip--}</td>
    <td align="center" {--$bgClass--}>{--$arr[loop].mac--}</td>
    <td align="center" {--$bgClass--}>{--$arr[loop].netmask--}</td>
    <td align="center" {--$bgClass--}><a href="modify.php?nId={--$arr[loop].ifid--}">编辑</a> | <a href="delete.php?nId={--$arr[loop].ifid--}" onClick="return confirm('确实要删除这个网卡吗？');">删除</a></td>
  </tr>
  {--/section--}
</table>
<br>
<br>
 this is good!
 
</body>
</html>
