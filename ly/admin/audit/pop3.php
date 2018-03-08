<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php
   require_once('_inc.php');
?>
<html>
<head>
	<script language="javascript" type="text/javascript" src="../js/calenderJS.js" defer="defer"></script>
	<script language="javascript" type="text/javascript" src="./ipcheck.js"></script>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	
	<link href="../common/main.css" rel="stylesheet" type="text/css" />
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
	<title>审计列表</title>

<script type="text/javascript">		
		jQuery(document).ready(function(){					
			jQuery("#list2").jqGrid({
				height:250,
				width:650,
			    url:'pop3data.php',
			    datatype: "json",
				   colNames:['时间','IP地址','解析'],
			    colModel:[
			  		{name:'logtime',index:'logtime',width:200,align:"left"},
			   		{name:'ip_inner',index:'ip_inner',width:150,align:"left"},   	
			  		{name:'analysis',index:'analysis',width:50,align:"left"}				
			    ],
			    pager: jQuery('#pager2'),
			    rowNum:20,
			    rowList:[20,40],
			    //imgpath: 'themes/basic/images',
			//	multiselect:true,
			    sortname: 'logtime',
			    viewrecords: true,
			    sortorder: "asc",
			    caption: "Pop3查询"
			});
			//	jQuery("#ips_list").jqGrid('navGrid','#pager',{edit:true,add:true,del:true,search: true});
		});		
</script>

</head>
<body>
	<h1>接收邮件列表</h1>
	<h2>
	<?php 
	if(isset($_SESSION['date']))
	{
		list($year,$month,$day)=explode('-',$_SESSION['date']);
		$nextmonth = mktime(0, 0, 0, $month+1,0,$year);
		echo "IP范围：".$_SESSION['ips']."/".$_SESSION['ipe']."&nbsp;日期范围：".$_SESSION['date']."/".date("Y-m-d",$nextmonth);
		if(isset($_SESSION['search'])) echo "&nbsp;关键词：".$_SESSION['search'];
	}
	else 
	{
		echo "请设置查询条件";
	}
	?></h2>		
	<table id="list2" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="pager2" class="scroll" style="text-align: center;"></div>	
</body>
</html>
