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
   error_reporting(E_ALL);
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
<title>黑白网址管理</title>
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
jQuery("#blackweb_list").jqGrid({
    url:'blackweb_data.php?nd='+new Date().getTime(),
	editurl:'black_web_buttonevent.php',
    datatype: "json",
	   colNames:['id','站点名称','阻挡/放行','描述'],
    colModel:[
  		{name:'id',index:'id', width:100,align:"right",hidden:true},
   		{name:'host',index:'host', width:70,align:"left",editable:true,editrules:{required:true}},
   		{name:'pass',index:'pass', width:100,align:"center",editable:true,editrules:{required:true}},
   		{name:'description',index:'description', width:150, align:"left",editable:true,editrules:{required:true}}				
    ],
    pager: jQuery('#pager'),
    rowNum:20,
    rowList:[20,40],
    height:500,
    width:1000,
    //imgpath: 'themes/basic/images',
	multiselect:false,
    sortname: 'id',
    viewrecords: true,
    sortorder: "asc",
    caption: "黑白网址列表"
});
jQuery("#blackweb_list").jqGrid('navGrid','#pager',{edit:false,add:false,del:false,search: false})
	.navButtonAdd('#pager',{   
   		caption:"del",    
   		buttonicon:"ui-icon-add",    
   		onClickButton: function(){    
			var id = jQuery("#blackweb_list").jqGrid('getGridParam','selrow');
			if (id)	{
				var answer = confirm("确实要删除选中的记录吗?");
				if(answer)
				{
					var ret = jQuery("#blackweb_list").jqGrid('getRowData',id);
					var frameurl="del_blackweb.php?id="+ret.id;
					var a = window.showModalDialog(frameurl,"","dialogHeight=0px;dialogWidth=0px;top=0;left=0;toolbar=no;menubar=no;scrollbars=no;scroll=no;resizable=no;location=no;status=no")
					if(a==1)
					{
						jQuery("#blackweb_list").trigger("reloadGrid");
					}
//					window.location.href="del_blackweb.php?id="+ret.id;
				}		
			} else { alert("请选择一条记录");}
		},    
   		position:"first"  
	}) 
	.navButtonAdd('#pager',{   
   		caption:"edit",    
   		buttonicon:"ui-icon-add",    
   		onClickButton: function(){    
			var id = jQuery("#blackweb_list").jqGrid('getGridParam','selrow');
			if (id)	{
				var ret = jQuery("#blackweb_list").jqGrid('getRowData',id);
		/*		$('#edit_blackweb').ShowDialog({
					Width:"500",
					Height:"400",
					Title:"编辑黑白网址",
					skin:"blue",
					FrameURL:"blackweb_edit.php?id="+ret.id+"&new_web=0",
					ContentFlag:"0",
					Contents:"<div>测试数据</div>"
					});*/
				var frameurl="blackweb_edit.php?id="+ret.id+"&new_web=0";
				var a = window.showModalDialog(frameurl,"","dialogHeight=400px;dialogWidth=500px;top=0;left=0;toolbar=no;menubar=no;scrollbars=no;scroll=no;resizable=no;location=no;status=no")
				if(a==1)
				{
					jQuery("#blackweb_list").trigger("reloadGrid");
				}
			} else { alert("请选择一条记录");}
		},    
   		position:"first"  
	})
	.navButtonAdd('#pager',{   
   		caption:"add",    
   		buttonicon:"ui-icon-add",    
   		onClickButton: function(){    
			var frameurl="blackweb_edit.php?new_blackweb=1";
			var a = window.showModalDialog(frameurl,"","dialogHeight=400px;dialogWidth=500px;top=0;left=0;toolbar=no;menubar=no;scrollbars=no;scroll=no;resizable=no;location=no;status=no")
			if(a==1)
				jQuery("#blackweb_list").trigger("reloadGrid");
		},    
   		position:"first"  
	});
});

function del_blackweb()
{
	var answer = confirm("确实要删除选中的记录吗?");
	if(answer)
		return true;
	else
		return false;
}

</script>
</head>

<body>
<div class="bodyTitle">
	<div class="bodyTitleLeft"></div>
  <div class="bodyTitleText">黑白网址管理</div>
</div>
<br>

<table id="blackweb_list" class="scroll" cellpadding="0" cellspacing="0"></table>

<!-- pager definition. class scroll tels that we want to use the same theme as grid -->
<div id="pager" class="scroll" style="text-align:center;"></div>

</body>

</html>

