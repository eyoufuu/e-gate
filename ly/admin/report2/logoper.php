<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<?php

session_start();
    if(!isset($_SESSION["date1"]))
       {
	     header("Location:./condition.php"); 
		 return;
	   }
   $ds = $_SESSION["date1"];
   $de = $_SESSION["date2"];

?>


<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<link rel="shortcut icon" type="image/ico" href="http://www.sprymedia.co.uk/media/images/favicon.ico" />
		<title>DataTables example</title>
		<style type="text/css" title="currentStyle">
			@import "./csss/demo_page.css";
			@import "./csss/demo_table.css";
			
		</style>
		<script type="text/javascript" language="javascript" src="./jss/jquery.js"></script>
		<script type="text/javascript" language="javascript" src="./jss/jquery.dataTables.js"></script>
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				$('#example').dataTable( {
				  
					"bProcessing": true,
		            "bServerSide": true,
					"sAjaxSource": "logoper_data.php"
				} );
			} );
		</script>
	</head>
	<body id="dt_example">
	
		<div id="container">
	         <div class="full_width big">
			 <i>管理员操作日志</i> 
			</div>
			<h1>选择的时间范围：<?php list($year,$month,$day)=explode('-',$ds);$tmp=mktime(0,0,0,$month+1,0,$year);echo $ds.'至';if($datee>$tmp){echo date("Y-m-d",$tmp);}else{echo $de;} ?>   </h1>			
<div id="dynamic">
    <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
			<th width="20%">日期</th>
			<th width="20%">操作</th>
			<th width="10%">资源</th>
			<th width="15%">管理员</th>
			<th width="10%">成功/失败</th>
		
		</tr>
	</thead>
	<tbody>
		<tr>
			<td colspan="5" class="dataTables_empty">正在读取服务器上的数据</td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<th width="20%">日期</th>
			<th width="20%">操作</th>
			<th width="10%">资源</th>
			<th width="15%">管理员</th>
			<th width="10%">成功/失败</th>
		
		</tr>
	</tfoot>
</table>
			</div>
			<div class="spacer"></div>
			
			
			
			
			<div id="footer" style="text-align:center;">
		  <span style="font-size:10px;">Copyright &copy;2010 Powered by <a href="http://www.lysafe365.com/">凌屹信息科技</a> <?php echo date('Y/m/d') ?></span>
		</div>
		</div>
	</body>
</html>