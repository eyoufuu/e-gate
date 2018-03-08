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
	width:100%;
	height:100px;
top:100%;
	align:right;
	font-size:12px;
	color:#555;
	font-family: Arial;
	margin-top: -100px;
	margin-right: auto;
	margin-bottom: 10px;
	margin-left: auto;
	position:absolute; 
	text-align:center;
}
</style>

<script>
String.prototype.GetValue= function(para) {  
	  var reg = new RegExp("(^|&)"+ para +"=([^&]*)(&|$)");  
	  var r = this.substr(this.indexOf("\?")+1).match(reg);  
	  if (r!=null) return unescape(r[2]); return null; 
}
function goon()
{
	var str=window.location.href;
	window.location.href ="http://"+str.GetValue("r");

}
</script>
<div style="line-height:200px">
<script>
document.write("<img align='absmiddle' src='../admin/company/pic/logo"+"?temp="+Date.parse(new Date())+"'"+" id='photo' width='150' height='75'/>" );
</script>
</div>


<h2 align="center">流量此类网站可能会额外消耗您的精力</h2>
<INPUT style="margin-left:500px;" type="button" value="继续" id="goon" size="20" onclick="return goon();" />	
<p>&nbsp;</p>
<div  class="copyright">
Copyright &copy;2010 Powered by <a href="http://www.lysafe365.com/">凌屹信息科技</a>
</div>
</body>
</html>
