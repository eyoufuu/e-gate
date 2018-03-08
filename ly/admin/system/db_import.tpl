

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>备份与恢复</title>
<link href="../common/main.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../common/common.js"></script>
<style type="text/css" title="currentStyle">
			@import "../js/js_new/demo_page.css";
			@import "../js/js_new/demo_table.css";
		</style>
</head>

<body>
  
  <h1>数据备份</h1>
</div>
<form name="form1" method="post" action="db_import.php">
       <p>
	      
         <input name="btnSubmit" type="submit" class="inputButton" id="btnSubmit" value=" 备份数据库 ">
		 <input name="symbol" type="hidden" id="user"  value="symbol" >
      </p>
</form>
  
  
  
  <h1>数据恢复</h1>
    {-- if $arr--}
    <table cellpadding="0" cellspacing="0" border="0"  class="display">
    	<thead>
        <tr>
        <th align="center">备份文件</th>
        <th align="center">大小</th>
        <th align="center">下载</th>
        <th align="center">恢复</th>
        <th align="center">删除</th>
        </tr>
        </thead>
        <tbody>
        {--section name=loop loop=$arr--}
        {--if $smarty.section.loop.index%2--}
        {--assign var=bgClass value=""--}
        {--else--}
        {--assign var=bgClass value="class='gradeA'"--}
        {--/if--}
        <tr {--$bgClass--}>
            <td align="center">{--$arr[loop].file--}</td>
            <td align="center">{--$arr[loop].size|filesize_format--}</td>
            <td align="center"><a href="download.php?action=db&file={--$arr[loop].file--}">下载此备份</a></td>
            <td align="center"><a href="?action=import&file={--$arr[loop].file--}" onClick="return confirm('您将使用“ {--$arr[loop].file--} ”这个备份数据恢复，是否确定？');">使用此备份恢复</a></td>
            <td align="center"><a href="?action=delete&file={--$arr[loop].file--}" onClick="return confirm('您将删除“ {--$arr[loop].file--} ”这个备份，是否确定？');">删除此备份</a></td>
        </tr>
        {--/section--}
        </tbody>
        <tfoot>
        <tr><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th></tr>
        </tfoot>
    </table>
    {--else--}
    目前还没有备份。
    {--/if--}
</body>
</html>