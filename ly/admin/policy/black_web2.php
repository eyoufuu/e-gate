<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php
   error_reporting(E_ALL);
   require_once('_inc.php');
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
<script type="text/javascript">

function checkip(ipaddr)
{
	var re=/^(\d+)\.(\d+)\.(\d+)\.(\d+)$/;//正则表达式   
	if(re.test(ipaddr))
	{
		if(RegExp.$1<256 && RegExp.$2<256 && RegExp.$3<256 && RegExp.$4<256)
			return true;
	}	
	return false;
}		
//黑白ip

jQuery(document).ready(function(){

jQuery("#blackip_list").jqGrid({
    url:'blackip_data.php',
	editurl:'blackip_edit.php',
    datatype: "json",
	colNames:['black_id','IP','阻挡/放行','描述'],
    colModel:[
		{name:'black_id',index:'black_id', width:1,align:"right",hidden:true},
		{name:'ip',index:'ip',width:150,align:"left",editable:true,editrules:{required:true}},    
		{name:'pass',index:'pass', width:100,align:"left",editable:true,edittype:"select",editoptions:{value:"1:放行;0:阻挡"}},
   		{name:'description',index:'description', width:250, align:"left",sortable:false,editable:true,edittype:"textarea", editoptions:{rows:"3",cols:"20"}}
   	],
	width:600,
    pager: jQuery('#ip_pager'),
    rowNum:50,
    rowList:[50,100],
    //imgpath: 'themes/basic/images',
 	multiselect:true,
    sortname: 'id',//数据库字段
    viewrecords: true,
    sortorder: "asc",
    caption: "黑白IP列表"
	});
	jQuery("#blackip_list").jqGrid('navGrid','#ip_pager',{edit:true,edittext:'编',add:true,addtext:'增',del:true,deltext:'删',search:false},
	{ 
		closeAfterAdd: true, 
	    closeAfterEdit: true,
	    afterShowForm:function(formid)
	    {
			$("#ip",formid).attr('disabled','disabled'); 		
	    },  
		onclickSubmit:function(postdata, formid)
		{
			var sr = jQuery("#blackip_list").getGridParam('selrow');
	        var rowData = jQuery("#blackip_list").getRowData(sr);
	        var retarr = {"bid" : rowData['black_id']};
	        return retarr; 
		}	 	
	},
	{
		closeAfterAdd: true, 
		closeAfterEdit: true, 
		beforeShowForm:function(formid)
		{
			$("#ip",formid).attr('disabled','');
		},
		beforeSubmit:function(postdata, formid)
		{
			if(checkip(postdata['ip'])==false )
				return[false,'IP地址不正确'];
			return [true,'ok'];
		}  
	  //  afterSubmit:processAddEdit, 
	    //beforeSubmit:validateData, 	   
	},
	{
		onclickSubmit:function(postdata, formid)
		{
		var sr = jQuery("#blackip_list").getGridParam('selrow');
        var rowData = jQuery("#blackip_list").getRowData(sr);
         var retarr = {"bid" : rowData['black_id']};
         return retarr; 
		}
	}
	);
});
function afterShowEdit(formId) {
	//$("#ip",formId).attr('disabled','disabled'); 		
	//$("#tr_ids",formId).val('1000'); 		
	//$("#tr_ids",formId).hide(); 		
    // alert("edit");
    //do stuff after the form is rendered 
} 
function afterShowADD(formId) {
	$("#ip",formId).attr('disabled','');
	 		
	//$("#tr_ids",formId).val('1000'); 		
	//$("#tr_ids",formId).hide(); 		
    // alert("edit");
    //do stuff after the form is rendered 
} 
function afterShowdel(formId){ 
 	
}

jQuery(document).ready(function(){

jQuery("#blackweb_list").jqGrid({
    url:'blackweb_data.php',
	editurl:'blackweb_edit2.php',
    datatype: "json",
	colNames:['black_id','站点名称','阻挡/放行','描述'],
    colModel:[
		{name:'black_id',index:'black_id', width:1,align:"right",hidden:true},
		{name:'host',index:'host',width:150,align:"left",editable:true,editrules:{required:true}},    
		{name:'pass',index:'pass', width:100,align:"left",editable:true,edittype:"select",editoptions:{value:"1:放行;0:阻挡"}},
   		{name:'description',index:'description', width:250, align:"left",sortable:false,editable:true,edittype:"textarea", editoptions:{rows:"3",cols:"20"}}
   	],
	width:600,
    pager: jQuery('#web_pager'),
    rowNum:50,
    rowList:[50,100],
    //imgpath: 'themes/basic/images',
 	multiselect:true,
    sortname: 'id',
    viewrecords: true,
    sortorder: "asc",
    caption: "黑白网址列表"
	});
	jQuery("#blackweb_list").jqGrid('navGrid','#web_pager',{edit:true,edittext:'编',add:true,addtext:'增',del:true,deltext:'删',search:false},
	{ 
		closeAfterAdd: true, 
	    closeAfterEdit: true,  
		onclickSubmit:function(postdata, formid)
		{
			var sr = jQuery("#blackweb_list").getGridParam('selrow');
	        var rowData = jQuery("#blackweb_list").getRowData(sr);
	         var retarr = {"bid" : rowData['black_id']};
	         return retarr; 
		}	 	
	},
	{
		beforeShowForm:afterShowADD,  
	  //  afterSubmit:processAddEdit, 
	    //beforeSubmit:validateData, 
	    closeAfterAdd: true, 
	    closeAfterEdit: true 
	},
	{
		onclickSubmit:function(postdata, formid)
		{
		var sr = jQuery("#blackweb_list").getGridParam('selrow');
        var rowData = jQuery("#blackweb_list").getRowData(sr);
         var retarr = {"bid" : rowData['black_id']};
         return retarr; 
		}
	}
	);
});
function afterShowEdit(formId) {
	//$("#ip",formId).attr('disabled','disabled'); 		
	//$("#tr_ids",formId).val('1000'); 		
	//$("#tr_ids",formId).hide(); 		
    // alert("edit");
    //do stuff after the form is rendered 
} 
function afterShowADD(formId) {
	//$("#ip",formId).attr('disabled',''); 		
	//$("#tr_ids",formId).val('1000'); 		
	//$("#tr_ids",formId).hide(); 		
    // alert("edit");
    //do stuff after the form is rendered 
} 
function afterShowdel(formId){ 	
}
</script>
</head>
<body>
    <h1>黑白IP名单</h1>
</div>
<table id="blackip_list" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="ip_pager" class="scroll" style="text-align:center;"></div>
<br/>
   <h1>黑白网址名单</h1>
<table id="blackweb_list" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="web_pager" class="scroll" style="text-align:center;"></div>
</body>
</html>
