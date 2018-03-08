<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理</title>
<link href="../common/main.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../../common/common.js"></script>
</head>

<body>
  <h1>添加管理员</h1>
<form action="" method="post" enctype="multipart/form-data" name="form1" onSubmit="return notEmpty(groupId, '请选择所属管理员组。')&&notEmpty(userName, '请输入管理员登录名。')&&notEmpty(userPwd, '请输入登录密码。')">
<div class="bgFleet paddingAll"> 
<table border="0" cellpadding="2" cellspacing="0">
  <tr>
    <td align="right"><span class="fontRed">* </span>所属管理员组：</td>
    <td>
      <select name="groupId" id="groupId">          
        <option value="">请选择</option>       
      {--mc_getcategories type="admin" varname="arrGroups"--}
      {--section name=loop loop=$arrGroups--}             
        <option value="{--$arrGroups[loop].f_id--}" {--if $groupId==$arrGroups[loop].f_id--}selected{--/if--}>{--$arrGroups[loop].f_name--}</option>        
      {--/section--}          
      </select>
    </td>
  </tr>
  <tr>
    <td align="right"><span class="fontRed">*</span> 登录名： </td>
    <td>
      <input name="userName" type="text" id="userName" maxlength="255">
    </td>
  </tr>
  <tr>
    <td align="right"><span class="fontRed">*</span> 登录密码：</td>
    <td>
      <input name="userPwd" type="text" id="userPwd" maxlength="255" autocomplete="off">
    </td>
  </tr>
  <tr>
    <td align="right"><span class="fontRed">*</span> 状态：</td>
    <td>
      <input name="status" type="radio" id="status" value="1" checked>启用
      <input name="status" type="radio" id="status2" value="0">禁用
    </td>
  </tr>
</table>
</div>
<br>
<input name="btnSubmit" type="submit" style="margin-left:500px;" class="inputButton_in" size="20" id="btnSubmit" value=" 提交 ">
</form>
</body>
</html>
