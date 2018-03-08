<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理</title>
<link href="../common/main.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../common/common.js"></script>

	<style type="text/css" title="currentStyle">
			@import "../js/js_new/demo_page.css";
			@import "../js/js_new/demo_table.css";
		</style>

</head>

<body>
  <h1>管理员列表</h1>
  <!-- 
<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td width="50" class="bgFleet borderBottom">编号</td>
    <td class="bgFleet borderBottom">名称</td>
    <td align="center" class="bgFleet borderBottom">管理员组</td>
    <td align="center" class="bgFleet borderBottom">状态</td>
    <td align="center" class="bgFleet borderBottom">操作</td>
  </tr>
 {--mc_getlist type="admin" group="$groupId" items="*" varname="arr"--}
 {--section name=loop loop=$arr--}
 {--if $smarty.section.loop.index%2--}
 {--assign var=bgClass value="class='bgFleet'"--}
 {--else--}
 {--assign var=bgClass value=""--}
 {--/if--}
  <tr>
    <td {--$bgClass--}>{--$arr[loop].f_id--}</td>
    <td {--$bgClass--}>{--$arr[loop].f_userName--}</td>
    <td align="center" {--$bgClass--}>{--$arr[loop].f_groupId--}</td>
    <td align="center" {--$bgClass--}>{--$arr[loop].f_status--}</td>
    <td align="center" {--$bgClass--}><a href="admin_admin_modify.php?aId={--$arr[loop].f_id--}">编辑</a> | <a href="admin_admin_delete.php?aId={--$arr[loop].f_id--}&gId={--$groupId--}" onClick="return confirm('确实要删除这个管理员吗？');">删除</a></td>
  </tr>
  {--/section--}
</table>
 -->
 <table cellpadding="0" cellspacing="0" border="0"  class="display">
 <thead>
  <tr>
    <th align="center" >编号</td>
    <th align="center" >名称</td>
    <th align="center" >管理员组</td>
    <th align="center" >状态</td>
    <th align="center" >操作</td>
  </tr>
  </thead>
  <tbody>
 {--mc_getlist type="admin" group="$groupId" items="*" varname="arr"--}
 {--section name=loop loop=$arr--}
 {--if $smarty.section.loop.index%2--}
 {--assign var=bgClass value=""--}
 {--else--}
 {--assign var=bgClass value="class='gradeA'"--} 
 {--/if--}
  <tr {--$bgClass--}>
    <td align="center">{--$arr[loop].f_id--}</td>
    <td align="center">{--$arr[loop].f_userName--}</td>
    <td align="center">{--$arr[loop].f_groupId--}</td>
    <td align="center">{--$arr[loop].f_status--}</td>
    <td align="center"><a href="admin_admin_modify.php?aId={--$arr[loop].f_id--}">编辑</a> | <a href="admin_admin_delete.php?aId={--$arr[loop].f_id--}&gId={--$groupId--}" onClick="return confirm('确实要删除这个管理员吗？');">删除</a></td>
  </tr>
  {--/section--}
  </tbody>
  <tfoot>
  <tr>
    <th align="center" >&nbsp;</td>
    <th align="center" >&nbsp;</td>
    <th align="center" >&nbsp;</td>
    <th align="center" >&nbsp;</td>
    <th align="center" >&nbsp;</td>
  </tr>
  </tfoot>
</table>
</body>
</html>