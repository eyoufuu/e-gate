<?php /* Smarty version 2.6.19, created on 2010-07-28 08:54:58
         compiled from top.tpl */ ?>
﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>导航</title>
<link href="common/top.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="topnav">
	<div class="sitenav">
		<div class="welcome">你好，<span class="username"><?php echo $this->_tpl_vars['adminName']; ?>
</span>，欢迎使用<a href="http://www.lysafe365.com/" target="_blank">凌屹信息科技</a> v<?php echo $this->_tpl_vars['systemVersion']; ?>
</div>
		<div class="sitelink"> 
			<a href="www.lysafe365.com" target="_blank">凌屹网站主页</a> | 
			<a href="menu.php?m=account" target="mcMenuFrame">我的账户</a> | 
			<a href="logout.php" target="_top">安全退出</a>
		</div>
	</div>
	<div class="leftnav">
		<ul>
			<li class="navleft"></li>
			<li id='d2'><a href="menu.php?m=product" target="mcMenuFrame">流量监控</a></li>			
			<li id='d2'><a href="menu.php?m=guestbook" target="mcMenuFrame">策略管理</a></li>
			<li id='d3'><a href="menu.php?m=job" target="mcMenuFrame">审计管理</a></li>
			<li id='d2'><a href="menu.php?m=other" target="mcMenuFrame">报表日志</a></li>
			<li id='d2'><a href="menu.php?m=usermanager" target="mcMenuFrame">用户管理</a></li>
			<li id='d1'><a href="menu.php?m=news" target="mcMenuFrame">配置管理</a></li>
			<li id='d1'><a href="menu.php?m=netscan" target="mcMenuFrame">网络扫描</a></li>
			<li id='d1' style="margin-left:-1px"><a href="menu.php?m=tools" target="mcMenuFrame">资产保护</a></li>			
			<li class="navright"></li>
		</ul>
	</div>
</div>
</body>
</html>