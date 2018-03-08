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
  <div class="bodyTitleText">修改密码</div>
</div>
<br>
<form action="" method="post" enctype="multipart/form-data" name="form1" onSubmit="return notEmpty(oldPwd, '请输入旧密码。')&&notEmpty(pwd1, '请输入新密码。')&&notEmpty(pwd2, '请再次输入新密码。')">
  <table border="0" cellpadding="2" cellspacing="0">
    <tr>
      <td align="right">用户：</td>
      <td class="fontBold">{--$adminName--}</td>
    </tr>
    <tr>
      <td align="right"><span class="fontRed">*</span> 旧密码：</td>
      <td>
        <input name="oldPwd" type="password" id="oldPwd" maxlength="255">
      </td>
    </tr>
    <tr>
      <td align="right"><span class="fontRed">*</span> 新密码：</td>
      <td>
        <input name="pwd1" type="password" id="pwd1" maxlength="255">
      </td>
    </tr>
    <tr>
      <td align="right"><span class="fontRed">*</span> 重复新密码：</td>
      <td>
        <input name="pwd2" type="password" id="pwd2" maxlength="255">
      </td>
    </tr>
  </table>
<br>
<input name="btnSubmit" type="submit" class="inputButton" id="btnSubmit" value=" 提交 ">
</form>
</body>
</html>