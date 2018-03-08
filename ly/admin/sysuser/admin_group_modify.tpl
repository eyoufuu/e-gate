{--mc_getcontent type="admingroup" id="$groupId" varname="groupInfo"--}
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理</title>
<link href="../common/main.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../../common/common.js"></script>
</head>

<body>
  <h1>修改管理员组</h1>
<form action="" method="post" enctype="multipart/form-data" name="form1" onSubmit="return notEmpty(name, '请输入管理员组名称。')">
<table border="0" cellpadding="2" cellspacing="0">
  <tr>
    <td align="right"><span class="fontRed">*</span> 管理员组名称： </td>
    <td>
      <input name="name" type="text" id="name" maxlength="255" value="{--$groupInfo.f_name--}">
    </td>
  </tr>
  <tr>
    <td align="right" valign="top"><span class="fontRed">*</span> 管理员组权限：</td>
    <td>
      <table>
        <tr> {--section name=loop loop=$arrPurviews--}
          {--if $smarty.section.loop.index%4==0--} </tr>
        <tr> {--/if--}
          <td>
          {--if strpos($groupInfo.f_purviews, str_pad($arrPurviews[loop].key, 2, "|", STR_PAD_BOTH))--}
          {--assign var="checked" value="checked"--}
          {--else--}
          {--assign var="checked" value=""--}
          {--/if--}
            <input name="arrPurviews[]" type="checkbox" value="{--$arrPurviews[loop].key--}" {--$checked--}>
            {--$arrPurviews[loop].name--}&nbsp;&nbsp;</td>
          {--/section--} </tr>
      </table>
    </td>
  </tr>
</table>
<br>
<input name="groupId" type="hidden" value="{--$groupId--}">
<input name="btnSubmit" type="submit" class="inputButton" id="btnSubmit" value=" 提交 ">
</form>
</body>
</html>
