<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" >       
<META   HTTP-EQUIV="pragma"   CONTENT="no-cache"> 
<link href="../common/main.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../common/common.js"></script>
</head>
<body>

<style>
.mid img{
	vertical-align:middle;
} 
.copyright {
	width:640px;
	align:right;
	font-size:12px;
	color:#555;
	font-family: Arial;
	margin-top: 50px;
	margin-right: auto;
	margin-bottom: 10px;
	margin-left: auto;
	position:relative; 
}
</style>
<div vertical-align="middle">
<script>
document.write("<img src=../company/pic/logo"+"?temp="+Date.parse(new Date())+" id='photo' width='150' height='75'/>" );
</script>
</div>
<?php 
$arr = "";
switch($_GET['p']){
	case 3:
		$arr = "关键字"; 
		break;
	case 1:
		$arr = "文件类型";
		break;
	case 2:
		$arr = "网站分类";
		break;
	case 4:
		$arr = "未知";
		break;
	default:		
		break;	
}
?>
<h1 align="center"><?php echo $arr; ?></h1>
<h1 align="center">您的请求被中断，请与管理员联系！</h1>
<p>&nbsp;</p>
<div class="copyright">
Copyright &copy;2010 Powered by <a href="http://www.lysafe365.com/">凌屹信息科技</a>
</div>
</body>
</html>
