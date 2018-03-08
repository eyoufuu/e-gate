{--mc_getcontent type="admin" id="$adminId" varname="adminInfo"--}
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理</title>
<link href="../common/main.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../../common/common.js"></script>
</head>

<body>
  <h1>修改管理员</h1>
<form action="" method="post" enctype="multipart/form-data" name="form1" onSubmit="return notEmpty(groupId, '请选择所属管理员组。')&&notEmpty(userName, '请输入管理员登录名。')">
<table border="0" cellpadding="2" cellspacing="0">
  <tr>
    <td align="right"><span class="fontRed">* </span>所属管理员组：</td>
    <td>
      <select name="groupId" id="groupId">
        <option value="">请选择</option>               
      {--mc_getcategories type="admin" varname="arrGroups"--}
      {--section name=loop loop=$arrGroups--}        
        <option value="{--$arrGroups[loop].f_id--}" {--if $adminInfo.f_groupId==$arrGroups[loop].f_id--}selected{--/if--}>{--$arrGroups[loop].f_name--}</option>                
      {--/section--}
      </select>
    </td>
  </tr>
  <tr>
    <td align="right"><span class="fontRed">*</span> 登录名： </td>
    <td>
      <input name="userName" type="text" id="userName" maxlength="255" value="{--$adminInfo.f_userName--}">
    </td>
  </tr>
  <tr>
    <td align="right"><span class="fontRed">*</span> 登录密码：</td>
    <td>
      <input name="userPwd" type="text" id="userPwd" maxlength="255" autocomplete="off" value="">
      (若不修改密码，请留空)
    </td>
  </tr>
  <tr>
    <td align="right"><span class="fontRed">*</span> 状态：</td>
    <td>
      <input name="status" type="radio" id="status" value="1" {--if $adminInfo.f_status==1--}checked{--/if--}>
      启用
      <input name="status" type="radio" id="status2" value="0" {--if $adminInfo.f_status==0--}checked{--/if--}>
      禁用 </td>
  </tr>
</table>
<br>
<input name="adminId" type="hidden" value="{--$adminId--}">
<input name="btnSubmit" type="submit" class="inputButton" id="btnSubmit" value=" 提交 ">
</form>
</body>
</html>
