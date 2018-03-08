<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "  http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<?php
   require_once('_inc.php');
?>


<html>
	<head>
	<title>报表条件</title>    
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link href="../common/main.css" rel="stylesheet" type="text/css"/>
	<link href="./css/tab.css" rel="stylesheet" type="text/css"/>
	
	
   
 
</head>
<style>.a3{width:30;border:0;text-align:center}</style>  
<body> 
   <strong>报表</strong>---> <strong>统计</strong>       
	<br>
	<form name=queryinput action="condition.php" method="post" >
		<div>
		<table border="0" cellpadding="2" cellspacing="0">
        <tr><td><input type="radio" name="mode" id="mode"  value="1" <?php  ?>> IP方式 </td></tr>
        <tr><td><input type="radio" name="lock" id="userid"  value="2" <?php  ?>>帐号方式</td></tr>
		</table>
     </div>
		<INPUT type="submit" name="提交" value="提交" id="submit" size="20" >	
	</form>
  
	

</body>
</html>
