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
  <div class="bodyTitleText">企业信息</div>
</div>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td width="30" class="bgFleet borderBottom">编号</td>
    <td class="bgFleet borderBottom">标题</td>
    <td align="center" class="bgFleet borderBottom">语言</td>
    <td align="center" class="bgFleet borderBottom">自动列出</td>
    <td align="center" class="bgFleet borderBottom">顺序</td>
    <td class="bgFleet borderBottom">操作</td>
  </tr>
 {--mc_getlist type="company" items="f_id, f_lang, f_list, f_lock, f_subject, f_order" id="$categoryId" orderby="f_order ASC, f_id ASC" page="$page" pagesize="20" varname="arr"--}
 {--section name=loop loop=$arr--}
 {--if $smarty.section.loop.index%2==1--}
 {--assign var=bgClass value="class='bgFleet'"--}
 {--else--}
 {--assign var=bgClass value=""--}
 {--/if--}
  <tr>
    <td align="center" {--$bgClass--}>{--$arr[loop].f_id--}</td>
    <td {--$bgClass--}><a href="../../?cn-c-d-{--$arr[loop].f_id--}.html" target="_blank">{--if $arr[loop].f_color--}<span style="color:{--$arr[loop].f_color--}">{--$arr[loop].f_subject--}</span>{--else--}{--$arr[loop].f_subject--}{--/if--}</a></td>
    <td align="center" {--$bgClass--}>{--$arr[loop].f_lang--}</td>
    <td align="center" {--$bgClass--}>{--if $arr[loop].f_list--}√{--else--}-{--/if--}</td>
    <td align="center" {--$bgClass--}>{--$arr[loop].f_order--}</td>
    <td {--$bgClass--}><a href="modify.php?iId={--$arr[loop].f_id--}&p={--$page--}">编辑</a>{--if $arr[loop].f_lock==0--} | <a href="delete.php?iId={--$arr[loop].f_id--}&p={--$page--}"" onClick="return confirm('确实要删除这个信息吗？');">删除</a>{--/if--}</td>
  </tr>
  {--/section--}
</table>
<br>
<div>
{--mc_getpagecount type="company" pagesize="20" varname="pagecount"--}
{--paginator page="$page" pagecount="$pagecount" url="?p=PAGENUMBER"--}
</div>
</body>
</html>
