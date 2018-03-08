<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理</title>
<link href="../common/main.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../../common/common.js"></script>
<script type="text/javascript" src="../../common/fckeditor/fckeditor.js"></script>
</head>

<body>
  <h1>添加管理员组</h1>
<form action="" method="post" enctype="multipart/form-data" name="form1" onSubmit="return notEmpty(name, '请输入管理员组名称。')">
<div class="bgFleet paddingAll"> 
<table border="0" cellpadding="2" cellspacing="0">
  <tr>
    <td align="right"><span class="fontRed">*</span> 管理员组名称： </td>
    <td>
      <input name="name" type="text" id="name" maxlength="255">
    </td>
  </tr>
  <tr>
    <td align="right" valign="top"><span class="fontRed">*</span> 管理员组权限：</td>
    <td>
      <table>     
      <tr> 
      {--section name=loop loop=$arrPurviews--}
      {--if $smarty.section.loop.index%4==0--}
      </tr>
      <tr>
      {--/if--}
        <td><input name="arrPurviews[]" type="checkbox" value="{--$arrPurviews[loop].key--}">{--$arrPurviews[loop].name--}&nbsp;&nbsp;</td>
      {--/section--}
      </tr>
      </table>
    </td>
  </tr>
</table>
</div>
<br>
<input name="btnSubmit" type="submit" class="inputButton_in" style="margin-left:500px;" size="20" id="btnSubmit" value=" 提交 ">
</form>
</body>
</html>
