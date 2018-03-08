{--mc_getcontent type="user" id="$userId" varname="user"--}
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
  <div class="bodyTitleText">用户信息</div>
</div>
<br>
  <table border="0" cellpadding="2" cellspacing="0">
    <tr>
      <td>用户名：</td>
      <td>
        {--$user.f_userName--}      </td>
    </tr>
    <tr>
      <td>姓名：</td>
      <td>{--$user.f_name--}</td>
    </tr>
    <tr>
      <td>单位名称：</td>
      <td>{--$user.f_company--}</td>
    </tr>
    <tr>
      <td>联系电话：</td>
      <td>{--$user.f_tel--}</td>
    </tr>
    <tr>
      <td>即时通讯：</td>
      <td>
        {--$user.f_im--}      </td>
    </tr>
    <tr>
      <td>电子邮件：</td>
      <td>
        {--$user.f_email--}      </td>
    </tr>
    <tr>
      <td>注册时间：</td>
      <td>
        {--$user.f_regTime|date_format:'%Y-%m-%d %H:%M:%S'--}      </td>
    </tr>
    <tr>
      <td>注册IP：</td>
      <td>
        {--$user.f_regIp--}      </td>
    </tr>
    <tr>
      <td>最后登录时间：</td>
      <td>{--$user.f_loginTime|date_format:'%Y-%m-%d %H:%M:%S'--}</td>
    </tr>
    <tr>
      <td>最后登录IP：</td>
      <td>{--$user.f_loginIp--}</td>
    </tr>
    <tr>
      <td>登录次数：</td>
      <td>{--$user.f_loginTimes--}</td>
    </tr>
  </table>
<br>
<input name="button" type="submit" class="inputButton" id="button" value=" 返回 " onClick="history.go(-1)">
</body>
</html>