<?php
/*
   * Modified: 2010-3-2
   * By: qianbo (qianbo@chd.edu.cn)
   *
   * Created: 2010-3-1
   * By: qianbo (qianbo@chd.edu.cn)
   *
*/ 
  error_reporting(E_ALL);

   require_once('_inc.php');

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>通道流量</title>
<link href="../common/main.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../common/common.js"></script>

<link rel="stylesheet" type="text/css" media="screen" href="themes/redmond/jquery-ui-1.7.1.custom.css" />
<link rel="stylesheet" type="text/css" media="screen" href="themes/ui.jqgrid.css" />
<link rel="stylesheet" type="text/css" media="screen" href="themes/ui.multiselect.css" />

<script src="js/jquery.js" type="text/javascript"></script>
<script src="js/jquery-ui-1.7.1.custom.min.js" type="text/javascript"></script>
<script src="js/jquery.layout.js" type="text/javascript"></script>
<script src="js/i18n/grid.locale-cn.js" type="text/javascript"></script>

<script src="js/jquery.jqGrid.min.js" type="text/javascript"></script>
<script src="js/jquery.tablednd.js" type="text/javascript"></script>
<script src="js/jquery.contextmenu.js" type="text/javascript"></script>
<script src="js/ui.multiselect.js" type="text/javascript"></script>


<script type="text/javascript">
jQuery(document).ready(function(){

	$("#show_stat").click(function () 
	{
		alert("status");
	});
});
</script>

<script type="text/javascript">
var x_trigger = "0";
var g_channel_value ="";
		/*$.getJSON("get_all_channel.php", function(g_json_object){
			g_all_channel_name = g_json_object;	
			g_channel_value = "";
			alert(g_all_channel_name.count);
			for(var i=0;i<g_all_channel_name.count;i++)
			{
				g_channel_value += g_all_channel_name.id[i]+":"+g_all_channel_name.name[i]+";";
			}
			return g_channel_value;
		});*/
//jQuery(document).ready(function(){
  function get_rules()
  {
jQuery("#list_rules").jqGrid({
    url:'data_rules.php?nd='+new Date().getTime(),
	editurl:'data_channel_edit.php',
    datatype: "json",
    colNames:['ID','通道名称','方式','值','描述','通道名称','方式'],
    colModel:[
  		{name:'id',index:'id', width:30,align:"right"},
  		{name:'name',index:'name', width:150,align:"center",editable:false,editrules:{required:true}  },
   		{name:'mode',index:'mode', width:150,align:"right",editable:false,editrules:{required:true,number:true}},
   		{name:'value',index:'value', width:150,align:"right",editable:true,editrules:{required:true,number:true}},
   		{name:'description',index:'description', width:100, align:"right",editable:true,editrules:{required:true,number:true}},
		{name:'nameselect', align:'center', hidden:true, editable:true, edittype:'select', editoptions:{value:g_channel_value}, editrules:{edithidden:true, searchhidden:true}, width:80 },
		{name:'modeselect', align:'center', hidden:true, editable:true, edittype:'select', editoptions:{value:'0:ip方式;1:ip段方式;2:协议方式'}, defval:'ip方式', editrules:{edithidden:true, searchhidden:true}, width:80 },

    ],
    pager: jQuery('#pager1'),
    rowNum:10,
    rowList:[10,20,30],
    //imgpath: 'themes/basic/images',
	multiselect:true,
    sortname: 'id',
    viewrecords: true,
    sortorder: "asc",
	onSelectRow: function(id){ 
	},
	beforeRequest:function(){
	   
		//get_all_channel();
		//alert(g_channel_value);
	},
    caption: "规则明细"
});
jQuery("#list_rules").jqGrid('navGrid','#pager1',{edit:true,add:true,del:true,search: true});
}
//});
</script>

<script type="text/javascript">
jQuery(document).ready(function(){

jQuery("#list_qos").jqGrid({
    url:'data_channel.php?nd='+new Date().getTime(),
	editurl:'data_channel_edit.php',
    datatype: "json",
    colNames:['ID','通道名称','上行带宽', '下行带宽', '优先级'],
    colModel:[
  		{name:'id',index:'id', width:30,align:"right"},
  		{name:'name',index:'name', width:150,align:"center",editable:true,editrules:{required:true}  },
   		{name:'rateup',index:'rateup', width:150,align:"right",editable:true,editrules:{required:true,number:true}},
   		{name:'ratedown',index:'ratedown', width:150,align:"right",editable:true,editrules:{required:true,number:true}},
   		{name:'priority',index:'priority', width:100, align:"right",editable:true,editrules:{required:true,number:true}},
    ],
    pager: jQuery('#pager'),
    rowNum:10,
    rowList:[10,20,30],
    //imgpath: 'themes/basic/images',
	multiselect:true,
    sortname: 'id',
    viewrecords: true,
    sortorder: "asc",
	beforeRequest:function(){
		//get_all_channel();
		//alert(g_channel_value);
	},
	loadComplete:function(){
			$.ajax({
			type: "POST",
			dataType: "json",
			async: false,
			cache:false,
			url: "get_all_channel.php",
			success: function(g_json_object)
			{
				g_channel_value = "";
				for(var i=0;i<g_json_object.count;i++)
				{
					g_channel_value += g_json_object.id[i]+":"+g_json_object.name[i];
					if(i<g_json_object.count-1)
						g_channel_value +=";";
				}
				
				/*
				jQuery("#list_rules").setGridParam({
				   colNames:['ID','通道名称','方式','值','描述','通道名称','方式'],
    colModel:[
  		{name:'id',index:'id', width:30,align:"right"},
  		{name:'name',index:'name', width:150,align:"center",editable:false,editrules:{required:true}  },
   		{name:'mode',index:'mode', width:150,align:"right",editable:false,editrules:{required:true,number:true}},
   		{name:'value',index:'value', width:150,align:"right",editable:true,editrules:{required:true,number:true}},
   		{name:'description',index:'description', width:100, align:"right",editable:true,editrules:{required:true,number:true}},
		{name:'nameselect', align:'center', hidden:true, editable:true, edittype:'select', editoptions:{value:g_channel_value}, editrules:{edithidden:true, searchhidden:true}, width:80 },
		{name:'modeselect', align:'center', hidden:true, editable:true, edittype:'select', editoptions:{value:'0:ip方式;1:端口方式;2:ip段方式'}, defval:'ip方式', editrules:{edithidden:true, searchhidden:true}, width:80 },

    ]
				});*/
				//alert(g_channel_value);
				jQuery("#list_rules").GridUnload();//trigger("unloadGrid");
				get_rules();
				//jQuery("#list_rules").trigger("reloadGrid");
			}
			});
    },	
    caption: "通道明细"
});
jQuery("#list_qos").jqGrid('navGrid','#pager',{edit:true,add:true,del:true,search: true});


});

</script>






</head>


<body>
<div class="bodyTitle">
	<div class="bodyTitleLeft"></div>
  <div class="bodyTitleText">高级流量控制</div>
</div>
<h1>QOS分配</h1>

&nbsp&nbsp QOS启用:<input type="checkbox" id="switch_qos" value="switch_qos" />

<h1>QOS明细</h1>
<table id="list_qos" class="scroll" cellpadding="0" cellspacing="0"></table>

<!-- pager definition. class scroll tels that we want to use the same theme as grid -->
<div id="pager" class="scroll" style="text-align:center;"></div>

<h1>QOS规则</h1>
<table id="list_rules" class="scroll" cellpadding="0" cellspacing="0"></table>

<!-- pager definition. class scroll tels that we want to use the same theme as grid -->
<div id="pager1" class="scroll" style="text-align:center;"></div>


<h1>QOS统计</h1>
<table id="qos_statics" class="scroll" cellpadding="0" cellspacing="0"></table>

<!-- pager definition. class scroll tels that we want to use the same theme as grid -->
<div id="pager2" class="scroll" style="text-align:center;"></div>
<input type = "button" id = "show_stat" class="show_stat" value="刷新">

</body>

</html>