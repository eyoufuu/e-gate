<?php
/*
   * Modified: 2010-3-2
   * By: qianbo (qianbo@chd.edu.cn)
   *
   * Created: 2010-3-1
   * By: qianbo (qianbo@chd.edu.cn)
   *
*/ 
   require_once('_inc.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script src="../js/jquery.js" type="text/javascript"></script>
<title>请输入条件</title>
<link href="../common/main.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript">
jQuery(document).ready(function(){
  	$("#ip_button").click(function () 
	{
		self.location="tc_p_everyday_data.php?IP="+$("#ip_text").attr("value");   
		//window.open(url)
	});
});
</script>
</head>
<body>

<h1>请输入IP地址</h1>
<div align = "center">

<input type="text" id = "ip_text" align = "center"/><input type="button" value = "确定" id="ip_button" align="center" class = "inputButton_in"/>
</div>
</body>
</html>