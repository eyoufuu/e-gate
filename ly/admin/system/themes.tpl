<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理</title>
<link href="../common/main.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../../common/common.js"></script>
</head>

<body>
<div class="bodyTitle">
	<div class="bodyTitleLeft"></div>
  <div class="bodyTitleText">模板设置</div>
</div>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td width="80" valign="top">当前模板：</td>
    <td>
      <table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td class="borderAll"><img src="../../themes/{--$currentTheme--}/preview.jpg" width="160" height="120" border="0"></td>
        </tr>
        <tr>
          <td align="center">{--$currentTheme--}</td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<br>
选择其他模板：
<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td>
      {--section name=loop loop=$arrThemes--}
      <table border="0" cellspacing="0" cellpadding="2" style="float:left;margin-right:10px">
        <tr>
          <td class="borderAll"><a href="?themeName={--$arrThemes[loop]--}"><img src="../../themes/{--$arrThemes[loop]--}/preview.jpg" alt="{--$arrThemes[loop]--}" width="160" height="120" border="0"></a></td>
        </tr>
        <tr>
          <td align="center">{--$arrThemes[loop]--}</td>
        </tr>
      </table>
      {--/section--}
    </td>
  </tr>
</table>
</body>
</html>