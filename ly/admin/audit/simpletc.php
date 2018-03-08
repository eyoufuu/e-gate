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

<?php
function getcolor()
{
	static $colorvalue;
	if($colorvalue=="'bgFleet'")
		$colorvalue="";
	else
		$colorvalue="bgFleet";
	return($colorvalue);
}
?>


<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>简单流控列表</title>
<link href="../common/main.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../common/common.js"></script>
<script language="javascript" src="./js_/json_1.js"></script>

<link rel="stylesheet" type="text/css" media="screen" href="themes/basic/grid.css" />
<!-- Of course we should load the jquery library -->
<script src="./js/jquery.js" type="text/javascript"></script>
<!-- and at end the jqGrid Java Script file -->
<script src="./js/jquery.jqGrid.js" type="text/javascript"></script>

<script src="./jquery.timePicker.js" type="text/javascript"></script>

<script type="text/javascript">
// We use a document ready jquery function.
jQuery(document).ready(function(){
jQuery("#list2").jqGrid({
    url:'data.php?nd='+new Date().getTime(),
    datatype: "json",
    colNames:['ID','起始IP','结束IP', '上行流量', '下行流量'],
    colModel:[
  		{name:'id',index:'id', width:20,align:"right"},
   		{name:'ips',index:'ips', width:150},
   		{name:'ipe',index:'ipe', width:150},
   		{name:'upbw',index:'upbw', width:100, align:"right"},
   		{name:'downbw',index:'downbw', width:100, align:"right"}				
    ],
    pager: jQuery('#pager2'),
    rowNum:10,
    rowList:[10,20,30],
    imgpath: 'themes/basic/images',
    sortname: 'id',
    viewrecords: true,
    sortorder: "asc",
    caption: "Demo"
});
});
</script>



<script language="javascript">

$("#time1").timePicker();

var g_json_object;
		var g_json;
        function GetUserNameValues(strValue)
        {
            $.get("ajax.php", {value:strValue}, function(text){
			//write_t.innerText=text;
              document.getElementById("write_t").innerText=text;
            });
        }
		function log(mes)
		{
		   write_t.innerText = mes;
		}
		function getdata(strValue)
		{
			$.getJSON("data.php", function(g_json_object){
			//g_json_object = json;
			log("ok");
			g_json_object.pop();
			//alert("姓名：" + json[0].name);
             /*for(var i = 0; i < json.length; i++)
             {
                 alert("姓名：" + json[i].name);
                 alert("年龄：" + json[i].age);
             }*/
			 
			 g_json = $.toJSON(g_json_object); 
			 write_t.innerText=g_json;

			});
		}
		
		function Post_Data(ips,ipe)
		{
			//post_json_string = $.toJSON(g_json_object);
			//write_t.innerText = post_json_string;
			alert("ips="+ips+"&ipe="+ipe);
			$.ajax({
			type: "POST",
			url: "simple_tc_post.php",
			data: "ips="+ips+"&ipe="+ipe,
			success: function()
			{
				alert( "数据已经成功保存: ");
			}
			})
		}

</script>


</head>

<body>
<div class="bodyTitle">
	<div class="bodyTitleLeft"></div>
  <div class="bodyTitleText">简单流量控制</div>
</div>
<br>

<table id="list2" class="scroll" cellpadding="0" cellspacing="0"></table>

<!-- pager definition. class scroll tels that we want to use the same theme as grid -->
<div id="pager2" class="scroll" style="text-align:center;"></div>
<br>

<input id="time1" name="time1"></input>

<form name="form" method="POST" action="action.php">
	<input type="edit" value="IP起始值" id="start_ip" />--<input type="edit" value="IP结束值" id="end_ip">
    <input class=btn tabIndex=20 type=button alt="保存完毕" value="保存" onclick="return Post_Data(start_ip.value,end_ip.value)">	
</form>
<br>

</body>

</html>
