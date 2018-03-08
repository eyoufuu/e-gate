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
  <h1>管理员组列表</h1>
<!-- <table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td width="50" class="bgFleet borderBottom">编号</td>
    <td class="bgFleet borderBottom">名称</td>
    <td align="center" class="bgFleet borderBottom">管理员列表</td>
    <td align="center" class="bgFleet borderBottom">添加管理员</td>
    <td align="center" class="bgFleet borderBottom">操作</td>
  </tr>
 {--mc_getcategories type="admin" items="*" varname="arr"--}
 {--section name=loop loop=$arr--}
 {--if $smarty.section.loop.index%2--}
 {--assign var=bgClass value="class='bgFleet'"--}
 {--else--}
 {--assign var=bgClass value=""--}
 {--/if--}
  <tr>
    <td {--$bgClass--}>{--$arr[loop].f_id--}</td>
    <td {--$bgClass--}>{--$arr[loop].f_name--}</td>
    <td align="center" {--$bgClass--}><a href="admin_admins.php?gId={--$arr[loop].f_id--}">管理员列表</a></td>
    <td align="center" {--$bgClass--}><a href="admin_admin_add.php?gId={--$arr[loop].f_id--}">添加管理员</a></td>
    <td align="center" {--$bgClass--}><a href="admin_group_modify.php?gId={--$arr[loop].f_id--}">编辑</a> | <a href="admin_group_delete.php?gId={--$arr[loop].f_id--}" onClick="return confirm('确实要删除这个管理员组吗？');">删除</a></td>
  </tr>
  {--/section--}
</table>
 -->
 <table cellpadding="0" cellspacing="0" border="0"  class="display">
 <thead>
 <tr>
    <th align="center">编号</td>
    <th align="center">名称</td>
    <th align="center">管理员列表</td>
    <th align="center">添加管理员</td>
    <th align="center">操作</td>
  </tr>
  </thead>
  <tbody>
 {--mc_getcategories type="admin" items="*" varname="arr"--}
 {--section name=loop loop=$arr--}
 {--if $smarty.section.loop.index%2--}
 {--assign var=bgClass value=""--}
 {--else--}
 {--assign var=bgClass value="class='gradeA'"--}
 {--/if--}
  <tr {--$bgClass--}>
    <td align="center" >{--$arr[loop].f_id--}</td>
    <td align="center" >{--$arr[loop].f_name--}</td>
    <td align="center" ><a href="admin_admins.php?gId={--$arr[loop].f_id--}">管理员列表</a></td>
    <td align="center" ><a href="admin_admin_add.php?gId={--$arr[loop].f_id--}">添加管理员</a></td>
    <td align="center" ><a href="admin_group_modify.php?gId={--$arr[loop].f_id--}">编辑</a> | <a href="admin_group_delete.php?gId={--$arr[loop].f_id--}" onClick="return confirm('确实要删除这个管理员组吗？');">删除</a></td>
  </tr>
  {--/section--}
  </tbody>
  <tfoot>
  <tr>
    <th align="center">&nbsp;</td>
    <th align="center">&nbsp;</td>
    <th align="center">&nbsp;</td>
    <th align="center">&nbsp;</td>
    <th align="center">&nbsp;</td>
  </tr>
  </tfoot>
</table>
</body>
</html>