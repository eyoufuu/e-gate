<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<link rel="shortcut icon" type="image/ico" href="http://www.sprymedia.co.uk/media/images/favicon.ico" />
		<title>DataTables example</title>
		<style type="text/css" title="currentStyle">
			@import "./csss/demo_page.css";
			@import "./csss/demo_table.css";
			@import "./csss/jquery-ui-1.7.2.custom.css";
		</style>
		<script type="text/javascript" language="javascript" src="./jss/jquery.js"></script>
		<script type="text/javascript" language="javascript" src="./jss/jquery.dataTables.js"></script>
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				$('#example').dataTable( {
				   "sDom": '<"top"i>rt<"bottom"flp<"clear">',
					
					"sAjaxSource": "server.php"
				} );
			} );
		</script>
	</head>
	<body id="dt_example">
		<div id="container">
			<div class="full_width big">
				<i>DataTables</i> server-side processing example
			</div>
			
			
			<div id="dynamic">
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
			<th width="20%">1</th>
			<th width="25%">Browser</th>
			<th width="25%">Platform(s)</th>
			<th width="15%">Engine version</th>
			<th width="15%">CSS grade</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td colspan="5" class="dataTables_empty">Loading data from server</td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<th width="20%">Rendering engine</th>
			<th width="25%">Browser</th>
			<th width="25%">Platform(s)</th>
			<th width="15%">Engine version</th>
			<th width="15%">CSS grade</th>
		</tr>
	</tfoot>
</table>
			</div>
			<div class="spacer"></div>
			
			
			
			
			<div id="footer" style="text-align:center;">
				<span style="font-size:10px;">DataTables &copy; Allan Jardine 2008-2010</span>
			</div>
		</div>
	</body>
</html>