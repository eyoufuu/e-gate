<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理</title>
<link href="../common/main.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../common/common.js"></script>
</head>

<body>
  <h1>数据备份</h1>
</div>
<form name="form1" method="post" action="db_export.php">
    <table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr>
            <td width="50" class="bgFleet borderBottom">&nbsp;</td>
            <td class="bgFleet borderBottom">数据表</td>
            <td class="bgFleet borderBottom">说明</td>
            <td align="right" class="bgFleet borderBottom">记录数</td>
            <td align="right" class="bgFleet borderBottom">大小</td>
        </tr>
        {--section name=loop loop=$arr--}
        {--if $smarty.section.loop.index%2--}
        {--assign var=bgClass value="class='bgFleet'"--}
        {--else--}
        {--assign var=bgClass value=""--}
        {--/if--}
        <tr>
         <td {--$bgClass--}>
             <input type="checkbox" name="arrTables[]" value="{--$arr[loop].Name--}" checked>
         </td>
            <td {--$bgClass--}>{--$arr[loop].Name--}</td>
            <td {--$bgClass--}>{--$arr[loop].Comment--}&nbsp;</td>
            <td align="right" {--$bgClass--}>{--$arr[loop].Rows--}</td>
            <td align="right" {--$bgClass--}>{--$arr[loop].Data_length|filesize_format--}</td>
        </tr>
        {--/section--}
    </table>
    <p>
        <input name="btnSubmit" type="submit" class="inputButton" id="btnSubmit" value=" 备份 ">
        </p>
</form>
</body>
</html>