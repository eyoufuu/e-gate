<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php
   require_once('_inc.php');
?>
<html>
	<head>
		<script language="javascript" type="text/javascript" src="../js/calenderJS.js" defer="defer"></script>
		<script language="javascript" type="text/javascript" src="./ipcheck.js"></script>	
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
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
	</head>
<style>.a3{width:30;border:0;text-align:center}</style>  
	<body>
		<h1>发送邮件列表</h1>
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
		<div id="log1" ></div>
		<table id="list2" class="scroll" cellpadding="0" cellspacing="0"></table>
		<div id="pager2" class="scroll" style="text-align:center;"></div>		
		<script type="text/javascript">
		function log(mes)
		{
			log1.innerText=mes;
		}
		function format(i)
		{ 
			return ('00'+i).slice(-2); 
		}		
		// We use a document ready jquery function.
		jQuery(document).ready(function(){
		
			jQuery("#list2").jqGrid({
				height:250,
				width:900,
			    url:'smtpdata.php',
			    datatype: "json",
				   colNames:['ID','时间','IP地址','MAC地址','源地址','目的地址','解析'],
			    colModel:[
			  		{name:'titleid',index:'id', width:30,align:"right"},
			  		{name:'logtime',index:'logtime', width:130,align:"left"},
			   		{name:'ip_inner',index:'ip_inner', width:100,align:"left"},
			   		{name:'mac',index:'mac', width:110,align:"left"},
			   		{name:'src',index:'src', width:150,align:"left"},
			   		{name:'dest',index:'dest', width:150,align:"left"},   	
			  		{name:'analysis',index:'analysis', width:50,align:"left"}				
			    ],
			    pager: jQuery('#pager2'),
			    rowNum:20,
			    rowList:[20,40],
			    //imgpath: 'themes/basic/images',
			//	multiselect:true,
			    sortname: 'titleid',
			    viewrecords: true,
			    sortorder: "asc",
			    caption: "Smtp查询"
			});			
		});	
		</script>
	</body>
</html>
