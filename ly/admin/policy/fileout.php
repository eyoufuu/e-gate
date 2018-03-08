<?php
require_once('_inc.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>文件外发</title>
<link href="../common/main.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../common/common.js"></script>

<link rel="stylesheet" type="text/css" media="screen" href="../themes/redmond/jquery-ui-1.7.1.custom.css" />
<link rel="stylesheet" type="text/css" media="screen" href="../themes/ui.jqgrid.css" />
<link rel="stylesheet" type="text/css" media="screen" href="../themes/ui.multiselect.css" />

<script src="../js/jquery.js" type="text/javascript"></script>
<script src="../js/jquery-ui-1.8.custom.min.js" type="text/javascript"></script>
<script src="../js/jquery.layout.js" type="text/javascript"></script>
<script src="../js/i18n/grid.locale-cn.js" type="text/javascript"></script>
<script src="../js/jquery.jqGrid.min.js" type="text/javascript"></script>
<script src="../js/jquery.tablednd.js" type="text/javascript"></script>
<script src="../js/jquery.contextmenu.js" type="text/javascript"></script>
<script src="../js/ui.multiselect.js" type="text/javascript"></script>
<script type="text/javascript">
function onShowHide(tt)
	{
		$("#"+tt).toggle();
	}
function on_select(tt)
	{
	    var x = "#check_all_" + tt;//总开关
		var value = $(x).attr("checked");
		for(var i = 0;i<g_json_select[tt].length;i++)
		{
			  var input = "#pro_id_" +g_json_select[tt][i].proid ;
			  $(input).attr("checked",value);
		}
		var div = "#div_id_" + tt;
		$(div).show();
	}
jQuery(document).ready(function(){
	function onDataReceived(json)
	{
	   g_json_select = json;
	   for(var item in json)
	   {
    	  var x = "#div_id_"+item;
		  $(x).append("&nbsp;&nbsp;&nbsp;")
		  for(var i =0;i<json[item].length;i++)
		  {
		      //alert(json[item][i].name);
			  var input = "<input id ='pro_id_" +json[item][i].proid +"' type='checkbox' />"+json[item][i].name+"&nbsp&nbsp";
			  $(x).append("&nbsp;")
			  $(x).append(input);
			  
		  }
		  $(x).append("<h3></h3>")
		  $(x).hide();
	   }
	}
	
	$.ajax({
        url: "fileout_data.php",
		cache:false,
        method: 'GET',
        dataType: 'json',
        success: onDataReceived
	});
});
</script>
</head>
<body>

<h2>列表</h2>

			 <?php 
			  $result = $db->query2("select * from procat where proid= -2 order by fileout","M",false);
			  foreach($result as $row)
			  {
			    echo "<table><tr>";
			    echo "<td height='20px'><input type='checkbox' id =".  "check_all_" . $row['fileout']  ." onclick= 'on_select(&quot;" . $row['fileout']. "&quot;)' /></td>";
				echo "<td height='20px'><a href = '#tabs-2' onclick='onShowHide(&quot;div_id_" . $row['fileout'] . "&quot;)'>" .  $row['name'] . "</a></td>";
				echo "</tr></table>";
				echo "<div style='vertical-align:middle' id = '" . "div_id_" . $row['fileout'] . "'></div>"; 
			  }
			  ?>
   <input type="button" style="width:70px;height:30px; "  name="submit" value="提交" onclick="">			  
	  
</body>

</html>