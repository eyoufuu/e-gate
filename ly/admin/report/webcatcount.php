<?php
error_reporting(E_ALL);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>网站分类</title>
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
    url:'webcatdata.php?nd='+new Date().getTime(),
	 datatype: "json",
	 colNames:['ID','时间','用户','ip','网站分类', 'host', '状态'],
    colModel:[
  		{name:'id',index:'id', width:30,align:"right"},
   	    {name:'logtime',index:'logtime', width:150,editable:true,editrules:{required:true}},
   	    {name:'name',index:'name', width:150,editable:true,editrules:{required:true}},
   	    {name:'ip_inner',index:'ip_inner', width:100, align:"right",editable:true,editrules:{required:true}},
        {name:'webcat',index:'webcat', width:100, align:"right",editable:true,editrules:{required:true}},
        {name:'host',index:'host', width:100, align:"right",editable:true,editrules:{required:true}},
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
    caption: "网站分类"
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
<h2>网站分类</h2>             
<br>
<table id="ips_list" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="pager" class="scroll" style="text-align:center;"></div>
</body>
</html>
