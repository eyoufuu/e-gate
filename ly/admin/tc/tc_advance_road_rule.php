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
<title>流控通道规则</title>
<link href="../common/main.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../common/common.js"></script>
<link rel="stylesheet" type="text/css" media="screen" href="../themes/redmond/jquery-ui-1.7.1.custom.css" />
<link rel="stylesheet" type="text/css" media="screen" href="../themes/ui.jqgrid.css" />
<link rel="stylesheet" type="text/css" media="screen" href="../themes/ui.multiselect.css" />
<script src="../js/jquery.js" type="text/javascript"></script>
<script src="../js/jquery-ui-1.7.1.custom.min.js" type="text/javascript"></script>
<script src="../js/jquery.layout.js" type="text/javascript"></script>
<script src="../js/i18n/grid.locale-cn.js" type="text/javascript"></script>
<script src="../js/jquery.jqGrid.min.js" type="text/javascript"></script>
<script src="../js/jquery.tablednd.js" type="text/javascript"></script>
<script src="../js/jquery.contextmenu.js" type="text/javascript"></script>
<script src="../js/ui.multiselect.js" type="text/javascript"></script>
<script type="text/javascript" src="table_set.js"></script>
<script type="text/javascript">
jQuery(document).ready(function(){
	$('#div_select_mode').hide();
	$('#div_select_mode_ips').hide();
	$('#div_select_mode_protocol').hide();
	$('#div_select_mode_ip').hide();
	$('#searchType').change(function(){
	   if (this.value == "1")
	   {
			$('#div_select_mode_ips').hide();
			$('#div_select_mode_protocol').hide();
			$('#div_select_mode_ip').fadeIn(500);
	   }
	   else if(this.value=="2")
	   {
			$('#div_select_mode_ip').hide();
			$('#div_select_mode_protocol').hide();
			$('#div_select_mode_ips').fadeIn(500);

	   }
	   else if(this.value=="3")
	   {
			$('#div_select_mode_ip').hide();
			$('#div_select_mode_ips').hide();
			$('#div_select_mode_protocol').fadeIn(500);
	   }
   });
   $('#edit_add_rules').click(function(){
		$('#div_select_mode').slideToggle(600);
		$('#div_select_mode_ip').show();
	});
   	$('#edit_rules').click(function()
    {
	});
   	$('#select_protocol').click(function(){
	         //var sequence =[];
			$('input[name=noticeSelect]:checked').each(function(){
				   var i = this.value;
					g_pro_catagory[i].channelname=g_channel_name;
     	   		   var str = "<tr><td align='center'><input type = checkbox value ="
							+g_pro_catagory[i].type +" name='noticegoSelect' align='center'></td>"+"<td>"+g_pro_catagory[i].type+"<td>"+g_pro_catagory[i].name
							+"</td>"+"</td><td>"+g_pro_catagory[i].des+"</td><td>"+g_pro_catagory[i].channelname+"</td></tr>";

					jQuery("#data_right tbody").append(str);
					g_pro_catagory[i].lr="r";
					
					//sequence.push(this.value);
					//sequence[i]='1';
				});
				jQuery("#data_left tbody").empty();
				for(var i in g_pro_catagory)
				{
				   if(g_pro_catagory[i].lr=="r")
				      continue;
					var str = "<tr><td align='center'><input type = checkbox value ="
							+g_pro_catagory[i].type +" name='noticeSelect' align='center'></td>"+"<td>"+g_pro_catagory[i].type+"<td>"+g_pro_catagory[i].name
							+"</td>"+"</td><td>"+g_pro_catagory[i].des+"</td><td>"+g_pro_catagory[i].channelname+"</td></tr>";
					jQuery("#data_left tbody").append(str);
				}
				//alert(sequence.join(','));
	});
	$('#unselect_protocol').click(function(){
				$('input[name=noticegoSelect]:checked').each(function(){
				   var i = this.value;
					g_pro_catagory[i].channelname=g_channel_name;
     	   		   var str = "<tr><td align='center'><input type = checkbox value ="
							+g_pro_catagory[i].type +" name='noticeSelect' align='center'></td>"+"<td>"+g_pro_catagory[i].type+"<td>"+g_pro_catagory[i].name
							+"</td>"+"</td><td>"+g_pro_catagory[i].des+"</td><td>"+g_pro_catagory[i].channelname+"</td></tr>";

					jQuery("#data_left tbody").append(str);
					g_pro_catagory[i].lr="l";
					
					//sequence.push(this.value);
					//sequence[i]='1';
				});
				jQuery("#data_right tbody").empty();
				for(var i in g_pro_catagory)
				{
				   if(g_pro_catagory[i].lr=="l")
				      continue;
					var str = "<tr><td align='center'><input type = checkbox value ="
							+g_pro_catagory[i].type +" name='noticegoSelect' align='center'></td>"+"<td>"+g_pro_catagory[i].type+"<td>"+g_pro_catagory[i].name
							+"</td>"+"</td><td>"+g_pro_catagory[i].des+"</td><td>"+g_pro_catagory[i].channelname+"</td></tr>";
					jQuery("#data_right tbody").append(str);
				}

	});
	//保存所有选择得协议，把以前得协议通道也修改了，最后保存得时候要判断是否错误！！
   	$('#save_protocol').click(function(){
		var dstr="-1=0";
		for(var i in g_pro_catagory)
		{
			if(g_pro_catagory[i].lr=="r")
			{
				dstr +='&';
				dstr += g_pro_catagory[i].type + "=";
				dstr += g_pro_catagory[i].channelname;
			}
		}
		function onDataReceived(json_ret)
		{
		   alert(json_ret.msg);
		}
	
		$.ajax({
            url: "tc_advance_road_rule_save.php",
			cache:false,
			type: "POST", 
            method: 'GET',
            dataType: 'json',
			data: dstr,
            success: onDataReceived
        });	
	   
	});
});
</script>

<script type="text/javascript">
	var g_pro_catagory;
	var g_channel_name;
	var g_channel_id;
	function initial_table()
	{
		jQuery("#data_left tbody").empty();
		jQuery("#data_right tbody").empty();

		function onDataReceived(json_obj)
		{
		   if(g_pro_catagory)
				g_pro_catagory.empty();
			g_pro_catagory = json_obj;
			for(var i in g_pro_catagory)
			{
			    var str;
			    if(g_pro_catagory[i].channelname==g_channel_name)
				{
					g_pro_catagory[i].lr="r";//这个表在右边
					str = "<tr><td align='center'><input type = checkbox value ="+g_pro_catagory[i].type;
					str += " name='noticegoSelect' align='center'></td><td>";
					str += g_pro_catagory[i].type+"</td><td>"+g_pro_catagory[i].name+"</td><td>"+g_pro_catagory[i].des+"</td><td>"+g_pro_catagory[i].channelname+"</td></tr>";
					jQuery("#data_right tbody").append(str);
				}
				else
				{
				    str = "<tr><td align='center'><input type = checkbox value ="+g_pro_catagory[i].type;
					str += " name='noticeSelect' align='center'></td><td>";
					str += g_pro_catagory[i].type+"</td><td>"+g_pro_catagory[i].name+"</td><td>"+g_pro_catagory[i].des+"</td><td>"+g_pro_catagory[i].channelname+"</td></tr>";
					jQuery("#data_left tbody").append(str);
				}
			}
		}
		$.ajax({
            url: "get_pro_catagory.php",
			cache:false,
            method: 'GET',
            dataType: 'json',
            success: onDataReceived
        });		
	}
jQuery(document).ready(function(){
	g_channel_name  = "<?php echo $_GET['channelname']?>";
	$('#channelname').html("<font color='red' ><B>" +g_channel_name+"</B></font>");
	initial_table();
});
</script>
<script type="text/javascript">
jQuery(document).ready(function(){
jQuery("#list_rules").jqGrid({
    url:'rules_data.php?channelid=<?php echo $_GET['channelid']?>',
	editurl:'rules_data_edit.php',
    datatype: "json",
    colNames:['ID','通道ID','通道名称','方式','值','描述'],
    colModel:[
  		{name:'ids',index:'ids', width:30,align:"right",editable:true},
		{name:'channelid',index:'channelid', width:50,align:"right"},
  		{name:'name',index:'name', width:150,align:"center"},
   		{name:'mode',index:'mode', width:150,align:"right"},
   		{name:'value',index:'value', width:150,align:"right",editable:true,editrules:{required:true,number:true}},
   		{name:'des',index:'des', width:150, align:"right",editable:true,editrules:{required:false}},
    ],
	
   	rowNum:100,
   	rowList:[100],
	pager:jQuery('#pager1'),
	multiselect:false,
    sortname: 'ids',
    viewrecords: true,
    sortorder: "asc",
	beforeRequest:function(){
	},
    caption: "规则明细"
});
jQuery("#list_rules").jqGrid('navGrid','#pager1',{edit:true,edittext:'编', del:true, 
deltext:'删'},
		{ 
                afterShowForm:afterShowEdit,  
                afterSubmit:processDelEdit, 
                //beforeSubmit:validateData, 
                closeAfterAdd: true, 
                closeAfterEdit: true 
        },  
		{ 
                afterShowForm:afterShowDel,  
                afterSubmit:processDelEdit, 
                //beforeSubmit:validateData, 
                closeAfterAdd: true, 
                closeAfterEdit: true 
        });
		function afterShowEdit(formId) {
				$("#tr_ids",formId).attr('disabled','disabled'); 		
				//$("#tr_ids",formId).hide(); 		
           
        } 
		function afterShowDel(formId){
		}
		function processAddEdit(formId){
		}
		function processDelEdit(response, postdata) { 
                var success = true; 
                var message = "" 
                var json = eval('(' + response.responseText + ')'); 
				alert(json.message);
                var new_id = "1"; 
                return [json.success,json.message,new_id]; 
        }


});
</script>
</head>
<body>
<div class="bodyTitle">
	<div class="bodyTitleLeft"></div>
  <div class="bodyTitleText">高级流量控制</div>
</div>
<h1>通道名称</h1>
<div id="channelname">
</div>
<h1>查看 删除QOS规则</h1>
<table id="list_rules" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="pager1" class="scroll" style="text-align:center;"></div>
<h1>编辑</h1>
<table border="0" cellpadding="2" cellspacing="0" >
<tr>
<td><input type="button" class="inputButton_se" id="edit_add_rules" value="增加"/></td>
</tr>
</table>
<div id = "div_select_mode" >
<table border="0" cellpadding="2" cellspacing="0" > 
       <tr><td> 请选择一种规则方式:</td>
	   <td>
      <select id="searchType">
		<option selected="selected" value ="1">IP方式</option>
		<option value="2">IP组方式 </option>
		<option value="3">协议方式</option>
      </select>
	  </td>
	  </tr>
	  <tr></tr>
</table> 
 </div>
   <div id = "div_select_mode_ip" class = "bgFleet paddingAll">
    <table border="0" cellpadding="2" cellspacing="0" >
        <tr><td>请输入输入IP地址  :</td> <td><input type = edit id = "mode_ip_edit" /></td></tr>
		<tr><td>请输入该IP的描述  :</td><td><input type =edit id ="node_ips_edit_des" size="100" maxlength="255"/></td></tr>
		<tr><td><input class="inputButton_se" type=button id ="save_mode_ip" value="保存" /> </td><tr>
	</table>	
   </div>
   <div id = "div_select_mode_ips" class = "bgFleet paddingAll">
    <table border="0" cellpadding="2" cellspacing="0" >
        <tr><td>请输入输入IP组地址:<input type = edit id = "mode_ips_edit_s" />-<input type = edit id = "mode_ips_edit_e" /></td></tr>
		<tr><td>请输入该组IP的描述:<input type =edit id ="node_ips_edit_des" size="100" maxlength="255"/></td></tr>
		<tr><td><input class="inputButton_se" type=button id ="save_mode_ips" value="保存" /> </td><tr>
	</table>	
   </div>
   
   <div id = "div_select_mode_protocol">
	<div id = "div_data_left" class = "leftBodyer" >
	<div class ="div_title" ><font color="white">选择需要控制得协议</font></div>
	<table class="warp_table_se" id="data_left">
	<caption height = "80" align="right"></caption>
	<thead><tr><th><input type = checkbox id ="check_left_all"></th><th>ID号</th><th>协议名称</th><th>协议描述</th><th>通道名称</th></tr></thead>
	<tbody></tbody>
	</table>
	</div>
	
	<div id ="div_man_middle" class="middleBodyer">
		<caption class="bodyTitleText">动作</caption>
		<br />
		<table  border="0" cellpadding="2" cellspacing="0" >
		<tr><td><input type="button"  class="inputButton_in" id="select_protocol" value="==>"/></td></tr>
		<tr><td><input type="button"  class="inputButton_in" id="unselect_protocol" value="<=="/></td></tr>
		<tr><td><input type="button"  class="inputButton_in" id="cancel_protocol" value="取消"></td></tr>	
		<tr><td><input type="button"  class="inputButton_in" id="save_protocol" value="保存"></td></tr>	
		</table>
		<br />
	</div>
	
	<div id = "div_data_right" class = "rightBodyer">
	<div class ="div_title" >被流量控制得协议</div>
	<table class="warp_table_se" id="data_right">
	<thead><tr><th><input type = checkbox id ="check_right_all"></th><th>ID号</th><th>协议名称</th><th>协议类型</th><th>通道名称</th></tr></thead>
	<tbody></tbody></table>
	</div>
	
	</div>
<script language="javascript">
	senfe("data_left","#f8fbfc","#e5f1f4","#ecfbd4","#bce774");
	senfe("data_right","#f8fbfc","#e5f1f4","#ecfbd4","#bce774");
	senfe("list_rules","#f8fbfc","#e5f1f4","#ecfbd4","#bce774");
</script>
	
</body>
</html>