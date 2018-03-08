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
<?php
function getcolor()
{
	static $colorvalue;
	if($colorvalue=="'bgFleet'")
		$colorvalue="";
	else
		$colorvalue="bgFleet";
	return($colorvalue);
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>网卡列表</title>
<link href="../common/main.css" rel="stylesheet" type="text/css"/>
<script language="javascript" type="text/javascript" src="jquery.js"></script> 
<script language="javascript" type="text/javascript" src="../common/common.js"></script>
</head>
<body>
<script id="source" language="javascript" type="text/javascript"> 
$(function () {
	
 $("input.bridge").click(function () 
	{
	
	   var data_post = [];
		var arr = $(':checkbox');
		for(var i=0;i<arr.length;i++){
		//arr[i].checked = ! arr[i].checked;
		   if(arr[i].checked)
		     data_post.push(i);
		}
		//alert(data_post);

/*	 $("input[@name=interface_card]").each(function(){
                            if(this.checked){
                                    aa+=this.value+",";
                            }
                    })
*/
   function onDataReceived()
   {
      alert("ok");
   }
    $.ajax({
        url: "put_bridge.php",
        method: 'GET',
		data:"data="+data_post,
        dataType: 'json',
		cache:false,
        success: onDataReceived
       });
	})

})
</script>


<div class="bodyTitle">
	<div class="bodyTitleLeft"></div>
  <div class="bodyTitleText">网络接口卡列表</div>
</div>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
  <td width="30" class ="bgFleet borderBottom">&nbsp&nbsp</td>
    <td width="40" class="bgFleet borderBottom">ID号</td>
    <td width="50" class="bgFleet borderBottom">接口名称</td>
    <td align="center" class="bgFleet borderBottom">类型</td>
    <td align="center" class="bgFleet borderBottom">模式</td>
    <td align="center" class="bgFleet borderBottom">连接</td>
    <td align="center" class="bgFleet borderBottom">带宽</td>
    <td align="center" class="bgFleet borderBottom">ip地址</td>
    <td align="center" class="bgFleet borderBottom">mac地址</td>
    <td align="center" class="bgFleet borderBottom">子网掩码</td>
    <td align="center" class="bgFleet borderBottom">操作</td>
  </tr>
<?php
$sql = "select * from cardinfo";
$arr = $db->fetchRows($sql);
foreach($arr as $value){
$color = getcolor();
?> 
<tr > 
<tr>
    <td align="center" class= <?php echo $color?>> <input name="interface_card" type="checkbox" id="interface_card[]"> </td>
    <td align="center" class= <?php echo $color?>> <?php echo $value['ifid']?></td>
    <td align="center" class= <?php echo $color?>><?php echo $value['name']?></td>
    <td align="center" class= <?php echo $color?>><?php echo $value['type']?></td>
    <td align="center" class= <?php echo $color?>><?php echo $value['mode']?></td>
    <td align="center" class= <?php echo $color?>><?php echo $value['link']?></td>
    <td align="center" class= <?php echo $color?>><?php echo $value['bandwidth']?></td>
    <td align="center" class= <?php echo $color?>><?php echo $value['ip']?></td>
    <td align="center" class= <?php echo $color?>><?php echo $value['mac']?></td>
    <td align="center" class= <?php echo $color?>><?php echo $value['netmask']?></td>
    <td class= <?php echo $color?> align = "center" ><a href="modify.php?ifid=<?php echo $value['ifid']?>>">编辑</a> | <a href="delete.php?ifid=<?php echo $value['ifid']?>" onClick="return confirm('确实要删除这个网卡吗？');">删除</a></td>
  </tr>

</tr> 
<?php
}
unset($arr);
?>



</table>
<br>
<br>
<input class ="bridge" name="bridge" id= "bridge" type="button" value="透明搭桥" />
<br>

</body>

</html>
