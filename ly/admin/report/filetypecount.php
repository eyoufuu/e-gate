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
<title>文件类型</title>
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
jQuery("#ips_list").jqGrid({
    url:'filetypedata.php?nd='+new Date().getTime(),
	 editurl:'simple_tc_edit.php',
    datatype: "json",
	 colNames:['ID','时间','用户','ip','host', '文件类型', '状态'],
    colModel:[
  		{name:'id',index:'id', width:30,align:"right"},
   	{name:'logtime',index:'logtime', width:150,editable:true,editrules:{required:true}},
   	{name:'name',index:'name', width:150,editable:true,editrules:{required:true}},
   	{name:'ip_inner',index:'ip_inner', width:100, align:"right",editable:true,editrules:{required:true}},
      {name:'host',index:'host', width:100, align:"right",editable:true,editrules:{required:true}},
   	{name:'filetype',index:'filetype', width:100, align:"right",editable:true,editrules:{required:true}},
   	{name:'pass',index:'pass', width:100, align:"right",editable:true,editrules:{required:true}},
   
     ],
    pager: jQuery('#pager'),
    height:500,
    width:700,
    rowNum:2,
    rowList:[2,4],
    imgpath: '../themes/basic/images',
	 multiselect:false,
    sortname: 'id',
    viewrecords: true,
    sortorder: "asc",
    caption: "文件类型"
});
jQuery("#ips_list").jqGrid('navGrid','#pager',{edit:false,add:false,del:false,search: true},
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
<h2>文件类型</h2>             
<br>
<table id="ips_list" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="pager" class="scroll" style="text-align:center;"></div>
</body>
</html>
