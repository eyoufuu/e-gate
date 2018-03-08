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
<title>通道流量</title>
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
jQuery(document).ready(function(){

	$("#show_stat").click(function () 
	{
		//get_rules('通道1')；
	});
});
</script>

<script type="text/javascript">
jQuery(document).ready(function(){
jQuery("#list_rules").jqGrid({
	url:'rules_data.php?nd='+new Date().getTime(),
    //url:'data.php?nd='+new Date().getTime(),
	//editurl:'simple_tc_edit.php',
    datatype: "json",
	   colNames:['ID','通道ID','通道名称', '方式', '值','描述'],
    colModel:[
  		{name:'ids',index:'ids', width:30,align:"right",editable:true},
		{name:'cid',index:'cid', width:150,editable:true,editrules:{required:true}},
   		{name:'name',index:'name', width:150,editable:true,editrules:{required:true}},
   		{name:'mode',index:'mode', width:100, align:"right",editable:true,editrules:{required:true}},
   		{name:'value',index:'value', width:100, align:"right",editable:true,editrules:{required:true}},				
   		{name:'des',index:'des', width:100, align:"right",editable:true,editrules:{required:true}}				
    ],
    pager: jQuery('#pager1'),
    rowNum:20,
    rowList:[20,40],
	multiselect:true,
    sortname: 'id',
    viewrecords: true,
    sortorder: "asc",
    caption: "ip组列表"
	});

/*
	jQuery("#list_rules").jqGrid({
    url:'data_rules.php',
	//editurl:'data_channel_edit.php',
    datatype: "json",
    colNames:['ID','通道ID','通道名称','方式','值','描述'],
    colModel:[
  		{name:'ids',index:'ids', width:30,align:"right"},
  		{name:'cid',index:'cid', width:50,align:"right"},
  		{name:'name',index:'name', width:150,align:"center",editable:false},
   		{name:'mode',index:'mode', width:150,align:"right",editable:false,editrules:{required:true,number:true}},
   		{name:'value',index:'value', width:150,align:"right",editable:true,editrules:{required:true,number:true}},
   		{name:'description',index:'description', width:100, align:"right",editable:true,editrules:{required:false,number:false}},
    ],
	
   	rowNum:100,
   	rowList:[100],
	pager:jQuery('#pager1'),
	multiselect:false,
    sortname: 'id',
    viewrecords: true,
    sortorder: "asc",
	beforeRequest:function(){
	},
    caption: "规则明细"
});*/
jQuery("#list_rules").jqGrid('navGrid','#pager1',{edit:true,add:false,del:false,search:false});
});
</script>

<script type="text/javascript">
jQuery(document).ready(function(){
jQuery("#list_qos").jqGrid({
    url:'channel_data.php?nd='+new Date().getTime(),
	editurl:'channel_data_edit.php',
    datatype: "json",
    colNames:['编号','通道名称','上行带宽', '下行带宽', '优先级','动作'],
    colModel:[
  		{name:'ids',index:'ids', width:40,align:"right"},
  		{name:'name',index:'name', width:150,align:"center",editable:true,editrules:{required:true}},
   		{name:'uprate',index:'uprate', width:100,align:"right",editable:true,editrules:{required:true,number:true}},
   		{name:'downrate',index:'downrate', width:100,align:"right",editable:true,editrules:{required:true,number:true}},
   		{name:'priority',index:'priority', width:100, align:"right",editable:true,editrules:{required:true,number:true}},
		{name:'act',index:'act',width:150,align:"center"},
    ],
    pager: jQuery('#pager'),
    rowNum:20,
    rowList:[20,40],
    sortname: 'id',
    viewrecords: true,
    sortorder: "asc",
	beforeRequest:function(){
	},
	
	gridComplete: function(){
		var ids = jQuery("#list_qos").jqGrid('getDataIDs');
		for(var i=0;i<ids.length;i++){
			var cl = ids[i];
			var ret = jQuery("#list_qos").jqGrid('getRowData',cl);
			//alert(ret.name);

			ge = "<a href ='tc_advance_road_rule.php?channelname="+ret.name+"&channelid=" +ret.ids+"'>" +"编辑规则"+"</a>";  
			jQuery("#list_qos").jqGrid('setRowData',ids[i],{act:ge});
		}	
	},
	
	loadComplete:function(){
		
    },/*	
	onSelectRow: function(id){
	    var id = jQuery("#list_qos").jqGrid('getGridParam','selrow');
		if (id)	
		{
			var ret = jQuery("#list_qos").jqGrid('getRowData',id);
			$('#channelname').html("<font color='blue'>"+ret.name+"</font>"+"的所有规则显示");
			//alert(ret.name);
			jQuery("#list_rules").GridUnload();//trigger("unloadGrid");
			get_rules(ret.name);		
		} 

	},*/
	onSelectRow: function(ids) {
		if(ids != null) 
		{
			var ret = jQuery("#list_qos").jqGrid('getRowData',ids);
			jQuery("#list_rules").jqGrid('setGridParam',{url:"rules_data.php?channelid="+ret.ids,page:1});
			jQuery("#list_rules").jqGrid('setCaption',ret.name+"的详细规则显示").trigger('reloadGrid');			
		}
	},

    caption: "通道明细"
});
jQuery("#list_qos").jqGrid('navGrid','#pager',{edit:true,add:true,del:true,search: false});
});

</script>
</head>
<body>
  <h1>高级流量控制</h1>
&nbsp&nbsp&nbsp&nbsp QOS启用:<input type="checkbox" id="switch_qos" value="switch_qos" />

<h1>QOS明细</h1>
<table id="list_qos" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="pager" class="scroll" style="text-align:center;"></div>
<h1>通道QOS规则</h1>
<div id = "channelname"></div>
<table id="list_rules" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="pager1" class="scroll" style="text-align:center;"></div>
</body>
</html>