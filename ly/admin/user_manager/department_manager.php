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
	if($colorvalue=="class='bgFleet'")
		$colorvalue="";
	else
		$colorvalue="class='bgFleet'";
	return($colorvalue);
}
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>网段管理</title>
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
	jQuery("#department_list").jqGrid({
	    url:'department_data.php?nd='+new Date().getTime(),
		editurl:'department_buttonevent.php',
	    datatype: "json",
		colNames:['id','网段名称','起始IP地址','结束IP地址','是否监控','分配策略'],
	    colModel:[
	  		{name:'id',index:'id', width:100,align:"right",hidden:true},
	   		{name:'department_name',index:'department_name', width:100,editable:true,editrules:{required:true}},
	   		{name:'ip_start',index:'ip_start', width:100,editable:true,editrules:{required:true}},
	   		{name:'ip_end',index:'ip_end', width:100,editable:true,editrules:{required:true}},
	   		{name:'monitor',index:'monitor', width:100,editable:true,editrules:{required:true}},
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
	    caption: "网段列表"
	});
	jQuery("#department_list").jqGrid('navGrid','#pager',{edit:false,add:false,del:false,search: false})
	.navButtonAdd('#pager',{   
   		caption:"del",    
   		buttonicon:"ui-icon-add",    
   		onClickButton: function(){    
			var id = jQuery("#department_list").jqGrid('getGridParam','selrow');
			if (id)	{
				var answer = confirm("确实要删除选中的网段吗?");
				if(answer)
				{
					var ret = jQuery("#department_list").jqGrid('getRowData',id);
					window.location.href="del_department.php?id="+ret.id;
				}		
			} else { alert("请选择一条记录");}  
		},    
   		position:"first"  
	}) 
	.navButtonAdd('#pager',{   
   		caption:"edit",    
   		buttonicon:"ui-icon-add",    
   		onClickButton: function(){    
			var id = jQuery("#department_list").jqGrid('getGridParam','selrow');
			if (id)	{
				var ret = jQuery("#department_list").jqGrid('getRowData',id);
				window.location.href="department_set.php?&new_department=0&id="+ret.id;
			} else { alert("请选择一条记录");}  
		},    
   		position:"first"  
	}) 
	.navButtonAdd('#pager',{   
   		caption:"Add",    
   		buttonicon:"ui-icon-add",    
   		onClickButton: function(){    
		window.location.href="department_set.php?new_department=1";   
		},    
   		position:"first"  
	});
/*	jQuery("#edit_department").click( function(){
		var id = jQuery("#department_list").jqGrid('getGridParam','selrow');
		if (id)	{
			var ret = jQuery("#department_list").jqGrid('getRowData',id);
			window.location.href="department_set.php?&new_department=0&id="+ret.id;
		} else { alert("请选择一条记录");}
	});

	jQuery("#create_department").click( function(){
			window.location.href="department_set.php?new_department=1";
	});

	jQuery("#del_department").click( function(){
		var id = jQuery("#department_list").jqGrid('getGridParam','selrow');
		if (id)	{
			var answer = confirm("确实要删除选中的网段吗?");
			if(answer)
			{
				var ret = jQuery("#department_list").jqGrid('getRowData',id);
				window.location.href="del_department.php?id="+ret.id;
			}		
		} else { alert("请选择一条记录");}
	});*/
	});

</script>
</head>

<body>
<div class="bodyTitle">
	<div class="bodyTitleLeft"></div>
  <div class="bodyTitleText">网段管理</div>
</div>
<br>

<table id="department_list" class="scroll" cellpadding="0" cellspacing="0"></table>

<!-- pager definition. class scroll tels that we want to use the same theme as grid -->
<div id="pager" class="scroll" style="text-align:center;"></div>
</body>

</html>

