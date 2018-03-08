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
<title>简单流控列表</title>
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
	jQuery("#tc_list_month").jqGrid({
    url:'tc_p_traffic_d.php?nd='+new Date().getTime(),
    datatype: "json",
	   colNames:['IP地址','当月上行流量(KB)','当月下行流量(KB)','总流量(KB)'],
    colModel:[
  		{name:'ip',index:'ip', width:100,align:"center"},
		{name:'up',index:'down', width:80,align:"right"},
   		{name:'down',index:'down', width:80,align:"right"},
   		{name:'total',index:'total', width:100, align:"right"}
    ],
	width:650,
    pager:jQuery('#pager_month'),
    rowNum:20,
    rowList:[20,40],
	multiselect:false,
    sortname: 'id',
    viewrecords: true,
    sortorder: "desc",
    caption: "月统计"
	});
	jQuery("#tc_list_month").jqGrid('navGrid','#pager_month',{edit:false,add:false,del:false,search:false});
});
	
</script>

<script type="text/javascript">

	jQuery(document).ready(function(){
	jQuery("#tc_list_day").jqGrid({
    url:'tc_p_traffic_day_d.php?nd='+new Date().getTime(),
    datatype: "json",
	   colNames:['IP地址','今日上行流量(KB)','今日下行流量(KB)','总流量(KB)'],
    colModel:[
  		{name:'ip',index:'ip', width:100,align:"center"},
		{name:'up',index:'down', width:80,align:"right"},
   		{name:'down',index:'down', width:80,align:"right"},
   		{name:'total',index:'total', width:100, align:"right"}
    ],
	width:650,
    pager: jQuery('#pager_day'),
    rowNum:20,
    rowList:[20,40],
	multiselect:false,
    sortname: 'id',
    viewrecords: true,
    sortorder: "desc",
    caption: "日统计"
	});
	jQuery("#tc_list_day").jqGrid('navGrid','#pager_day',{edit:false,add:false,del:false,search:false});
});
	
</script>
</head>
<body>
	<h1>月和日流量展示</h1>
 <li>单位:KB</li> 
<h1>当日总流量--IP统计</h1>
<li><?php echo date('Y-m-d')?></li>
<table id="tc_list_day" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="pager_day" class="scroll" style="text-align:center;"></div>


<h1>当月总流量--IP统计</h1>
<li><?php echo date('Y-m')?></li>
<table id="tc_list_month" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="pager_month" class="scroll" style="text-align:center;"></div>

</body>
</html>