<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>简单流控列表</title>
<link href="../common/main.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../common/common.js"></script>
<link rel="stylesheet" type="text/css" media="screen" href="themes/redmond/jquery-ui-1.7.1.custom.css" />
<link rel="stylesheet" type="text/css" media="screen" href="themes/ui.jqgrid.css" />
<link rel="stylesheet" type="text/css" media="screen" href="themes/ui.multiselect.css" />

<script src="js/jquery.js" type="text/javascript"></script>
<script src="js/jquery-ui-1.7.1.custom.min.js" type="text/javascript"></script>
<script src="js/jquery.layout.js" type="text/javascript"></script>
<script src="js/i18n/grid.locale-cn.js" type="text/javascript"></script>
<script src="js/jquery.jqGrid.min.js" type="text/javascript"></script>
<script src="js/jquery.tablednd.js" type="text/javascript"></script>
<script src="js/jquery.contextmenu.js" type="text/javascript"></script>
<script src="js/ui.multiselect.js" type="text/javascript"></script>

 <script type="text/javascript">
 jQuery(document).ready(function(){
 jQuery("#interface_list").jqGrid({
    url:'shell_interface.php?nd='+new Date().getTime(),
    datatype: "json",
	   colNames:['ID','IP地址','子网掩码','mac地址', '是否激活', '接收包','发送包','接收字节数','发送字节数'],
    colModel:[
  		{name:'id',index:'id', width:30,align:"right"},
   		{name:'ip',index:'ip', width:150},
   		{name:'netmask',index:'netmask', width:150},
   		{name:'mac',index:'mac', width:150},				
   		{name:'ifup',index:'ifup', width:50},
   		{name:'rxp',index:'rxp', width:100},				
   		{name:'txp',index:'txp', width:100},			
   		{name:'rxb',index:'rxb', width:100},				
   		{name:'txp',index:'txp', width:100},				
    ],
    pager: jQuery('#pager'),
    rowNum:20,
    rowList:[20,40],
    imgpath: 'themes/basic/images',
	multiselect:true,
    sortname: 'id',
    viewrecords: true,
    sortorder: "asc",
	//loadComplete:function(g_json_object)
	//{
	//}
    caption: "网卡列表"
   jQuery("#ips_list").jqGrid('navGrid','#pager',{edit:false,add:false,del:false,search: false},
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
<?php
  require_once("shell_interface.php");
  $mac = new GetMacAddr();   
  echo "<pre>";   
  print_r( $mac->mac_addr);   
  echo "</pre>";
?>   
</body>

</html>
