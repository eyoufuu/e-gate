<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php 
require_once ('_inc.php');
 /*$name = $_POST['companyname'];
 $web = $_POST['companyweb'];
 $addr = $_POST['companyaddr'];
 $phone = $_POST['companyphone'];
 $detail =$_POST['companypdetail']; 
 $logo = $_POST['companylogo'];
 $format = "insert into companyinfo (name,web,addr,phone,detail,logo) values(%s,%s,%s,%s,%s,%s)";
 $sql = sprintf($format,$name,$web,$addr,$phone,$detail,$logo);*/
 $sql = "select * from companyinfo";
 // echo $sql;
// $arr = $db->query("delete from companyinfo");
 $arr = $db->fetchRow($sql); 
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理</title>
<link href="../common/main.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../common/common.js"></script>
</head>
<body>
<h1>公司信息编辑</h1>
	<form action="actionpost.php" method="post" enctype="multipart/form-data" >
	<table border="0" cellpadding="2" cellspacing="0">
	<tr>
	<td>公司名称：</td><td><input type="text" name="companyname" size=50 maxlength="200" value="<?php echo $arr['name'];?>"/></td>
	</tr>
	<tr>
	<td>公司网址：</td><td><input type="text" name="companyweb" size=50 maxlength="200" value="<?php echo $arr['web'];?>"/></td>
	</tr>
	<tr>
	<td>公司地址：</td><td><input type="text" name="companyaddr" size=50 maxlength="200" value="<?php echo $arr['addr'];?>"/></td>
	</tr>
	<tr>
	<td>联系电话：</td><td><input type="text" name="companyphone" size=50 maxlength="50" value="<?php echo $arr['phone'];?>"/></td>
	</tr>
	<tr>
	<td>公司logo：</td><td><input type="file" name="companylogo"  size=50 value="浏览" ></td>
	</tr>	
	<tr>
	<td>详细信息：</td><td></td>
	</tr>
	</table>
	<textarea name="companydetail" style="width:80%; height:200px" ><?php echo $arr['detail']?></textarea>
	<input type="submit" class="inputButton_in" style="margin-left:500px;" size="20" value="确定" />
	</form>
</body>
</html>








