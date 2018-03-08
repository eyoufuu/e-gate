<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php
   require_once('_inc.php');
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<script language="javascript" type="text/javascript" src="../js/calenderJS.js" defer="defer"></script>
		<script language="javascript" type="text/javascript" src="./ipcheck.js"></script>
		
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
		
		<title>审计列表</title>
	 <div id="log1" ></div> 
		
		<script type="text/javascript">
		function log(mes)
		{
			log1.innerText=mes;
		}
		
		// We use a document ready jquery function.
		jQuery(document).ready(function(){		
			jQuery("#list2").jqGrid({
				height:250,
				width:900,
			    url:'postdata.php',
			    datatype: "json",
				colNames:['时间','IP地址','MAC地址','HOST','URL','解析'],
			    colModel:[
			  		{name:'logtime',index:'logtime', width:130,align:"left"},
			   		{name:'ip_inner',index:'ip_inner', width:100,align:"left"},
			   		{name:'mac',index:'mac', width:110,align:"left"},
			   		{name:'host',index:'host', width:150,align:"left"},
			   		{name:'url',index:'url', width:150,align:"left"},   	
			  		{name:'analysis',index:'analysis', width:50,align:"left"}				
			    ],
			    pager: jQuery('#pager2'),
			    rowNum:20,
			    rowList:[20,40],
			    //imgpath: 'themes/basic/images',
			//	multiselect:true,
			    sortname: 'logtime',
			    viewrecords: true,
			    sortorder: "asc",
			    caption: "Post查询"
			});			
		});
		function submit_check()
		{
			if(checkinput())
			{			
				return true;
			}
			else
			{
				return false;
			}			
		}		
		</script>
		
	</head>
	<body>
	<h1>发帖列表</h1>
	<?php 
	if(isset($_SESSION['date']))
	{
		list($year,$month,$day)=explode('-',$_SESSION['date']);
		$nextmonth = mktime(0, 0, 0, $month+1,0,$year);
		echo "<h2>IP范围：".$_SESSION['ips']."/".$_SESSION['ipe']."</h2>";
		echo "<h2>日期范围：".$_SESSION['date']."/".date("Y-m-d",$nextmonth)."</h2>";
		if(isset($_SESSION['search'])) echo "<h2>&nbsp;关键词：".$_SESSION['search']."</h2>";
	}
	else 
	{
		echo "<h2>请设置查询条件</h2>";
	}
	?>
		<table id="list2" class="scroll" cellpadding="0" cellspacing="0"></table>
		<div id="pager2" class="scroll" style="text-align:center;"></div>		
	</body>
</html>
