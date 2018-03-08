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
   include("../dbconfig.php");
?>   
<?php
function getcolor()
{
	static $colorvalue;
	if($colorvalue=="bgFleet")
		$colorvalue="";
	else
		$colorvalue="bgFleet";
	return($colorvalue);
}

function getcolor_int()
{
	static $n = true;
	$n=!$n;
	return $n;
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>简单流控列表</title>
<link href="../common/main.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../common/common.js"></script>
<link rel="stylesheet" type="text/css" media="screen" href="../themes/basic/grid.css" />
<script src="js/jquery.js" type="text/javascript"></script>
<script src="js/jquery.jqGrid.js" type="text/javascript"></script>


<script type="text/javascript">
jQuery(document).ready(function(){

   function onDataReceived(ifbind)
   {
	  if(ifbind="1")
	  {
	    $("#ip_mac_yes").attr("checked",true);
	  }
	  else
	  {
       	$("#ip_mac_no").attr("checked",true);  
	  }
   }
   $.ajax({
        url: "get_ip_mac_bind_g.php",
        method: 'GET',
        dataType: 'json',
		cache:false,
        success: onDataReceived
       });  
 });
</script>
<script type="text/javascript">
jQuery(document).ready(function(){

$("#checkall").click(function() { 
	$("input[name='select_ip_mac[]']").each(function() { 
		$(this).attr("checked", true); 
	}); 
	}); 
$("#delcheckall").click(function() { 
	$("input[name='select_ip_mac[]']").each(function() { 
	$(this).attr("checked", false); 
	}); 
	}); 

$("#delete_ip_mac").click(function(){
   function delete_success(data)
   {
      alert(data);
	   document.location.reload();
   }
   ips = "0";
   var i = 0;
	$("input[name='select_ip_mac[]']").each(function() { 
	  if($(this).attr("checked") == true)
      {
	     i++;
		 ips += "," + $(this).attr("value") ;
	  }	  
	});
	alert(ips);
    if(i==0)
    {
	   alert("请选择要删除得条目");
    }	
	else
	{
	 $.post("delete_multi.php",{ ip_dels:ips  },  
	   function(data){
	      alert(data);
	   });
	}
   
});

		jQuery("#bind_or_not").click(function(){
	   function get_bind_or_not()
	   {
	      if($("#ip_mac_yes").attr("checked")==true)
		  {
		     return 1;
		  }
		  return 0;
	   }
	   function onBind_or_not()
	   {
			alert("保存成功");
	   }
	   $.ajax({
	      url: "ip_mac_bind.php",
		  method:'GET',
		  cache:false,
		  data:"permit="+get_bind_or_not(),
		  success:onBind_or_not
	   });
	})

function check_ip(test_ip)
{

	var pattern=/^(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9])\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])$/;
//flag_ip=pattern.test(document.all.ip.value);
	flag_ip = pattern.test(test_ip);
	if(!flag_ip)
	{
	//document.all.ip.focus();
		return false;
	}
	return true;
}

function check_mac(test_mac)
{

	var pattern=/^([0-9A-Fa-f]{2})(-[0-9A-Fa-f]{2}){5}|([0-9A-Fa-f]{2})(:[0-9A-Fa-f]{2}){5}/;
    flag_mac = pattern.test(test_mac);
	if(!flag_mac)
	{
		return false;
	}
	return true;
}
	
	jQuery("#ip_mac_add").click(function(){
	    alert("check ip");
	    var ip_add_v = $("#edt_ip_add").attr("value");
		var mac_add_v = $("#edt_mac_add").attr("value");
		var memo_add_v = $("#edt_memo_add").attr("value");
	   if(!check_ip(ip_add_v))
	   {
	      alert("ip地址输入错误!");
		  return false;
	   }
	   if(!check_mac(mac_add_v))
	   {
		  alert("mac输入错误!");
		  return false;
	   }
	   
	   $.getJSON("add_ip_mac.php",{ ip_add: ip_add_v, mac_add:mac_add_v, memo_add:memo_add_v  },  
	   function(data){
	    alert(data.res);
		if(data.res=='0')
		{
		   alert("保存失败 "+data.mac);
		}
		else
		{
		   alert("保存成功");
		   document.location.reload();
		}
	});
  })
	 

	jQuery("#ip_mac_all").click(function(){
	if(this.checked){ 
		$("input[name='select_ip_mac[]']").each(function(){this.checked=true;}); 
	}else{ 
			$("input[name='select_ip_mac[]']").each(function(){this.checked=false;}); 
		} 
     })
});

</script>

<script type="text/javascript"> 
function h_s(in_edit)
{
	var mac = document.getElementById(in_edit); 
	var memo = document.getElementById(in_edit+"_memo");
	var but  = document.getElementById(in_edit+"_bu");
	mac.readOnly = false;
	mac.className = "s";
	mac.focus();
	memo.readOnly = false;
	memo.className = "s";
	but.style.display = "";
	//mysubmit.style.display = ""; 
 }
function post_data(ip_d,mac_id,mac_v,memo_id,memo_v)
{
	$.getJSON("save_ip_mac.php",{ ip: ip_d, mac:mac_v, memo:memo_v  },  
	function(data){
	    alert(data.res);
		if(data.res==0)
		{
			var mac = document.getElementById(mac_id); 
			var memo = document.getElementById(mac_id+"_memo");
			mac.value = data.mac;
			memo.value = data.memo;
			
		   alert("保存失败 "+data.mac);
		}
		else
		   alert("保存成功");
	});
}
function save(in_mac,in_ip)
{
	var mac = document.getElementById(in_mac); 
	var ip =  document.getElementById(in_ip);
	var memo = document.getElementById(in_mac+"_memo");
	var but  = document.getElementById(in_mac+"_bu");
	mac.readOnly = true;
	mac.className = "t";
	memo.readOnly = true;
	memo.className = "t";
	but.style.display = "none";
	post_data(ip.value,mac.id,mac.value,memo.id,memo.value);
} 
</script> 




<body>
<div class="bodyTitle">
	<div class="bodyTitleLeft"></div>
  <div class="bodyTitleText">ip-mac绑定</div>
</div>

<br /> 
<br />
<form action = "" method = "post"> 
<input type="radio" name="ip_mac" id = "ip_mac_yes" value = "no"  />绑定&nbsp&nbsp
<input type="radio" name="ip_mac" id = "ip_mac_no" value = "yes"  />不绑定&nbsp&nbsp
<input type="button" id="bind_or_not" value = "保存">
<br />
<br />
<br />
ip地址:<input title ="输入IP地址" type ="edit" value =""  id="edt_ip_add" background-color: #E8F3FD />&nbsp&nbsp mac地址:<input title ="输入MAC地址" type ="edit" value ="" id = "edt_mac_add"/>&nbsp&nbsp
备注:<input title ="输入备注" type ="edit" value =""  id="edt_memo_add"/>&nbsp
<input type = "button" value = "增加" id = "ip_mac_add"/>
<br />
<br />
<table id = "tab_ip_mac" width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
  <td width="30" align = "center" class ="bgFleet borderBottom"><input name="ip_mac_all" id ="ip_mac_all" type="checkbox" /></td>
    <td width="200" align = "left" class="bgFleet borderBottom">ip地址</td>
    <td width="250" align = "left" class="bgFleet borderBottom">mac地址</td>
	<td width = "100" align = "center" class="bgFleet borderBottom">可选</td>
    <td align="left" class="bgFleet borderBottom">备注</td>
    <td align="center" class="bgFleet borderBottom">操作</td>
  </tr>
<?php
$sql = "select * from ipmac";
$arr = $db->fetchRows($sql);
foreach($arr as $value)
{
    $flag = getcolor_int();
	$color="";
	$select = "checked";
	
	if($flag==true)
	{
		$color ="bgFleet" ;
	}
	if($value['bind'] == 0)
	{
		$select = "";
	}
	
?> 
<tr>
    <td width = "30"  align="center" class= "<?php echo $color?>" > <input name="select_ip_mac[]" type="checkbox" id="select_ip_mac[]" value= "<?php echo $value['ip']?>" /> </td>
    <td width = "200" align="left" class= "<?php echo $color?>" > 
	   <input name = "ip[]"  class = "t" readonly id = "<?php echo $value['ip']?>" value ="<?php echo $value['ip']?>">
	</td>   
    <td width = "250" align="left" class= "<?php echo $color?>" > 
	   <input name = "mac[]" class="t" id="<?php echo $value['mac']?>" readonly value="<?php echo $value['mac']?>" />
	</td>
	<td width = "100"class="<?php echo $color?>" align = "center">
	   <input type = "checkbox"  <?php echo  $select?> />
	</td>
    <td class= "<?php echo $color?>" align ="left">
	   <input name="memo[]" class = "t" id = "<?php echo $value['mac'].'_memo'?>" readonly value ="<?php echo $value['memo']?>" >
	   <input type="button" value="保存" style="display:none;" id="<?php echo $value[mac].'_bu'?>"  onclick= "save('<?php echo $value['mac']?>', '<?php echo $value['ip']?>' )" /> 
	</td>   
    <td class= "<?php echo $color?>" align = "center" ><a href="#" onclick= "h_s('<?php echo $value['mac']?>')">编辑</a> | <a href="delete.php?ip=<?php echo $value['ip']?>" onClick="return confirm('确实要删除这一条记录?');">删除</a></td>
  </tr>

<?php
}
unset($arr);
?>
</table>
<br />

<input type="button" value="全选" id="checkall"/>
<input type="button" value="全不选" id="delcheckall"/>
<input type="button" value="删除" id="delete_ip_mac"/>


</form> 




</body>

</html>