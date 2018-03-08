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
<title>简单流控列表</title>
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
jQuery("#ips_list").jqGrid({
    url:'data.php?nd='+new Date().getTime(),
	 editurl:'simple_tc_edit.php',
    datatype: "json",
	 colNames:['ID','网卡名称','方式', 'mac地址', '状态'],
    colModel:[
  		{name:'id',index:'id', width:30,align:"right"},
   		{name:'ips',index:'ips', width:150,editable:true,editrules:{required:true}},
   		{name:'ipe',index:'ipe', width:150,editable:true,editrules:{required:true}},
   		{name:'upbw',index:'upbw', width:100, align:"right",editable:true,editrules:{required:true}},
   		{name:'downbw',index:'downbw', width:100, align:"right",editable:true,editrules:{required:true}}				
    ],
    pager: jQuery('#pager'),
    rowNum:20,
    rowList:[20,40],
    imgpath: 'themes/basic/images',
	multiselect:true,
    sortname: 'id',
    viewrecords: true,
    sortorder: "asc",
    caption: "ip组列表"
});
jQuery("#ips_list").jqGrid('navGrid','#pager',{edit:true,add:true,del:true,search: true},
	{
	 closeOnEscape: true,
	 afterSubmit : function(r, postdata) 
	 {
	    
	    //alert("ok");
		//alert(r.responseText);
		var data = eval('(' + r.responseText + ')' ); 
		alert(data.message);
		return [data.success, data.message, 0];
	 }
	 }
);
});
</script>
</head>
<body>
<div class="bodyTitle">
	<div class="bodyTitleLeft"></div>
	<div class="bodyTitleText">网卡列表</div>
</div>
<br>
<table id="ips_list" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="pager" class="scroll" style="text-align:center;"></div>
</body>
</html>
