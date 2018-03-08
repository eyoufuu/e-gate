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
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>用户管理</title>
<link href="../common/main.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../common/common.js"></script>

<link rel="stylesheet" type="text/css" media="screen" href="../themes/redmond/jquery-ui-1.7.1.custom.css" />
<link rel="stylesheet" type="text/css" media="screen" href="../themes/ui.jqgrid.css" />
<link rel="stylesheet" type="text/css" media="screen" href="../themes/ui.multiselect.css" />
<script src="../js/jquery.js" type="text/javascript"></script>
<script src="../js/dialog.js"></script>
<script src="../js/jquery-ui-1.7.1.custom.min.js" type="text/javascript"></script>
<script src="../js/jquery.layout.js" type="text/javascript"></script>
<script src="../js/i18n/grid.locale-cn.js" type="text/javascript"></script>

<script src="../js/jquery.jqGrid.min.js" type="text/javascript"></script>
<script src="../js/jquery.tablednd.js" type="text/javascript"></script>
<script src="../js/jquery.contextmenu.js" type="text/javascript"></script>
<script src="../js/ui.multiselect.js" type="text/javascript"></script>

<script type="text/javascript">

jQuery(document).ready(function(){
	jQuery("#user_list").jqGrid({
	    url:'user_data.php?nd='+new Date().getTime(),
		editurl:'user_buttonevent.php',
	    datatype: "json",
		colNames:['id','用户名称','登陆账号','用户IP地址','所属网段','分配策略'],
	    colModel:[
	  		   {name:'id',index:'id', width:100,align:"right",hidden:true},
	   		{name:'user_name',index:'user_name', width:100,editable:true,editrules:{required:true}},
	   		{name:'user_account',index:'user_account', width:100,editable:true,editrules:{required:true}},
	   		{name:'user_ip',index:'user_ip', width:100,editable:true,editrules:{required:true}},
	   		{name:'user_netseg',index:'user_netseg', width:100,editable:true,editrules:{required:true}},
	   		{name:'user_policy',index:'user_policy', width:100,editable:true,editrules:{required:true}}			
	    ],
	    pager: jQuery('#pager'),
	    rowNum:20,
	    rowList:[20,40],
	    height:500,
	    width:1000,
		multiselect:false,
	    sortname: 'id',
	    viewrecords: true,
	    sortorder: "asc",
	    caption: "用户列表"
	});
	jQuery("#user_list").jqGrid('navGrid','#pager',{edit:false,add:false,del:false,search: false})
	.navButtonAdd('#pager',{   
   		caption:"del",    
   		buttonicon:"ui-icon-add",    
   		onClickButton: function(){    
		var id = jQuery("#user_list").jqGrid('getGridParam','selrow');
		if (id)	{
			var answer = confirm("确实要删除选中的用户吗?");
			if(answer)
			{
				var ret = jQuery("#user_list").jqGrid('getRowData',id);
				window.location.href="del_user.php?id="+ret.id;
			}		
		} else { alert("请选择一条记录");}
		},    
   		position:"first"  
	}) 
	.navButtonAdd('#pager',{   
   		caption:"edit",    
   		buttonicon:"ui-icon-add",    
   		onClickButton: function(){    
			var id = jQuery("#user_list").jqGrid('getGridParam','selrow');
			if (id)	{
				var ret = jQuery("#user_list").jqGrid('getRowData',id);
				window.location.href="user_set.php?new_user=0&id="+ret.id;
			} else { alert("请选择一条记录");} 
		},    
   		position:"first"  
	}) 
	.navButtonAdd('#pager',{   
   		caption:"add",    
   		buttonicon:"ui-icon-add",    
   		onClickButton: function(){    
			window.location.href="user_set.php?new_user=1";
		},    
   		position:"first"  
	});
	});

function loginmode_change()
{
  document.form_mode.submit();
 
}

</script>
</head>

<body>

<div class="bodyTitle">
	<div class="bodyTitleLeft"></div>
  <div class="bodyTitleText">用户管理</div>
</div>
<?php
$sql_loginmode="select systemmode from globalpara;";
$arr_mode=$db->fetchRows($sql_loginmode);
$loginmode=$arr_mode[0]['systemmode'];
if($loginmode==0)
{
	$login_mode_ip="selected";
	$login_mode_account="";
}
else
{
	$login_mode_ip="";
	$login_mode_account="selected";
}

?>
<form name="form_mode" id="form_mode" action="save_loginmode.php" method="post">
请选择用户登录模式：
<select   name="loginmode_select"   id="loginmode_select" onChange="loginmode_change()"> 
<option   value=0   <?php echo $login_mode_ip;?>>IP方式</option>
<option   value=1   <?php echo $login_mode_account;?>>账号登陆方式</option>
</select>
<br>
<font color=green>说明：账号方式指员工上网时如果其IP地址未在管理列表内，则需要输入账号密码，一般在动态分配IP地址的网络中使用；IP方式一般在使用静态分配IP地址的网络中使用，管理员可以通过IP地址来对应到相应的上网人员。</font>
<br>

<table id="user_list" class="scroll" cellpadding="0" cellspacing="0"></table>

<!-- pager definition. class scroll tels that we want to use the same theme as grid -->
<div id="pager" class="scroll" style="text-align:center;"></div>
</form>
</body>

</html>

