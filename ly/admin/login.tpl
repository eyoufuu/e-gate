<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>凌屹企业网监系统</title>
<script type="text/javascript" src="../common/common.js"></script>
<style type="text/css">
<!--
* {
	padding:0px;
	margin:0px;
	font-family:Verdana, Arial, Helvetica, sans-serif;
}
body {
	margin: 0px;
	font-size:12px;
	color: #555;
}
input {
	vertical-align:middle;
}
img {
	border:none;
	vertical-align:middle;
}
a {
	color:#2366A8;
	text-decoration: none;
}
a:hover {
	color:#2366A8;
	text-decoration:underline;
}
.main {
	width:640px;
	background:#FFF;
	padding-bottom:10px;
	margin-top: 120px;
	margin-right: auto;
	margin-bottom: 0px;
	margin-left: auto;
}
.main .title {
	width:600px;
	height:50px;
	margin:0px auto;
	background:url(images/login_toptitle.jpg) -10px 0px no-repeat;
	text-indent:326px;
	line-height:46px;
	font-size:14px;
	letter-spacing:2px;
	color:#F60;
	font-weight:bold;
}
.main .login {
	width:630px;
	margin:20px auto 0px;
	overflow:hidden;
}
.main .login .inputbox {
	width:230px;
	float:right;
	background-image: url(images/login_input_hr.gif);
	background-repeat: no-repeat;
	background-position: left center;
	padding-left: 20px;
}
.main .login .inputbox dl {
	height:28px;
	clear:both;
}
.main .login .inputbox dl dt {
	float:left;
	width:50px;
	height:25px;
	line-height:25px;
	text-align:right;
	padding-right: 5px;
}
.main .login .inputbox dl dd {
	width:160px;
	float:left;
	padding-top:1px;
}
.main .login .inputbox dl dd input {
	font-size:12px;
	border:1px solid #dcdcdc;
	background:url(images/login_input_bg.gif) left top no-repeat;
	height: 18px;
	line-height: 18px;
	padding-right: 2px;
	padding-left: 2px;
}
.main .login .inputbox dl .input {
	width:57px;
	height:23px;
	background:url(images/login_submit.gif) no-repeat;
	border:none;
	cursor:pointer;
}
.main .login .butbox {
	float:left;
	width:350px;
}
.main .login .butbox dl {
}
.main .login .butbox dl dt {
	height:76px;
	padding-top:5px;
	background-image: url(images/logo_b.jpg);
	background-repeat: no-repeat;
}
.main .login .butbox dl dd {
	height:21px;
	line-height:21px;
}
.main .msg {
	clear:both;
	line-height:20px;
	border:1px solid #DCDCDC;
	color:#888;
	margin-left: 55px;
	background-color: #FFFFFF;
	padding-right: 2px;
	padding-left: 2px;
	width: 130px;
	margin-bottom: 3px;
}
.copyright {
	width:640px;
	text-align:center;
	font-size:12px;
	color:#555;
	font-family: Arial;
	margin-top: 50px;
	margin-right: auto;
	margin-bottom: 10px;
	margin-left: auto;
}
.copyright a {
	color:#2366a8;
	text-decoration:none;
}
.copyright a:hover {
	text-decoration: underline;
}
-->
</style>
</head>
<body>
<div class="main">
  <div class="login">
    <form action="login.php" method="post" onSubmit="return notEmpty(userName, '请输入用户名。')&&notEmpty(userPwd, '请输入密码。')">
      <input type="hidden" name="dopost" value="login">
      <div class="inputbox">
        <dl>
          <dt>用户名：</dt>
          <dd>
            <input type="text" name="userName" id="userName" size="20" onfocus="this.style.borderColor='#239fe3'" onblur="this.style.borderColor='#dcdcdc'" />
          </dd>
        </dl>
        <dl>
          <dt>密　码：</dt>
          <dd>
            <input type="password" name="userPwd" id="userPwd" size="20" onfocus="this.style.borderColor='#239fe3'" onblur="this.style.borderColor='#dcdcdc'" />
          </dd>
        </dl>
        <dl>
          <dt>&nbsp;</dt>
          <dd>
            <input name="submit" type="submit" value="" class="input" />
          </dd>
        </dl>
      </div>
      <div class="butbox">
        <dl>
          <dt></dt>
          <dd> 凌屹信息科技行为管理平台系统是一个采用 底层高端技术 构建的高效企业网络行为管理解决方案。&nbsp;&nbsp; <a href="http://www.lysafe365.com" target="_blank">官方网站</a> | <a href="www.lysafe365.com/bbs/" target="_blank">技术论坛</a></dd>
        </dl>
      </div>
      <div style="clear:both"></div>
    </form>
  </div>
</div>
<div class="copyright">Copyright &copy;2010 Powered by <a href="http://www.lysafe365.com/">凌屹信息科技</a> v{--$systemVersion--}</div>
{--if $msg--}<script type="text/javascript">alert("{--$msg--}");</script>{--/if--}
</body>
</html>
