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
//  echo $sql;
// $arr = $db->query("delete from companyinfo");
 $arr = $db->fetchRow($sql); 
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" >
       
<META   HTTP-EQUIV="pragma"   CONTENT="no-cache">
 
<link href="../common/main.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../common/common.js"></script>
</head>
<body>

<a href="<?php echo $arr['web'];?>" target="_blank" >

<script>
document.write("<img align='right' src=./pic/logo"+"?temp="+Date.parse(new Date())+" id='photo' width='150' height='75'/>" );
</script>
</a>
<h1 align="center"><?php echo $arr['name']; ?></h1>
<!--  <span style="text-indent:2em;"></span>-->
<?php 
$str=str_replace(" ","&nbsp;",$arr['detail']);
echo  str_replace("\r\n","<br>",$str);
 
?>
<p>&nbsp;</p>
<span align="right">主页：<?php echo $arr['web'];?></span><br/>
<span align="right">地址：<?php echo $arr['addr'];?></span><br/>
<span align="right">电话：<?php echo $arr['phone'];?></span><br/>

</body>
</html>
