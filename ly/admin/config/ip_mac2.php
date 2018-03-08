<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php
/*
   * Modified: 2010-3-2
   * By: qianbo (qianbo@chd.edu.cn)
   *
   * Created: 2010-3-1
   * By: qianbo (qianbo@chd.edu.cn)
   *
*/ 
 // error_reporting(E_ALL);
   require_once('_inc.php');
 /*  $sql = "select isipmacbind from globalpara";
   $isbind = $db->query2one($sql,"全局变量",true);
   */
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ip mac地址绑定功能页</title>
<link href="../common/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../common/common.js"></script>
<link rel="stylesheet" type="text/css" media="screen"
	href="../themes/redmond/jquery-ui-1.7.1.custom.css" />
<link rel="stylesheet" type="text/css" media="screen"
	href="../themes/ui.jqgrid.css" />
<link rel="stylesheet" type="text/css" media="screen"
	href="../themes/ui.multiselect.css" />
<script src="../js/jquery.js" type="text/javascript"></script>
<script src="../js/jquery-ui-1.8.custom.min.js" type="text/javascript"></script>
<script src="../js/jquery.layout.js" type="text/javascript"></script>
<script src="../js/i18n/grid.locale-cn.js" type="text/javascript"></script>
<script src="../js/jquery.jqGrid.min.js" type="text/javascript"></script>
<script src="../js/jquery.tablednd.js" type="text/javascript"></script>
<script src="../js/jquery.contextmenu.js" type="text/javascript"></script>
<script src="../js/ui.multiselect.js" type="text/javascript"></script>
<script type="text/javascript">
/*function myelem (value, options) {
	  var el = document.createElement("input");
	  el.type="text";
	  el.value = value;
	  el.disabled = 'disabled';
	  return el;
	}
	 
	function myvalue(elem) {
	  return $(elem).val();
	}*/
function checkip(ipaddr)
{
	var re=/^(\d+)\.(\d+)\.(\d+)\.(\d+)$/;//正则表达式   
	if(re.test(ipaddr))
	{
		if(RegExp.$1<256 && RegExp.$2<256 && RegExp.$3<256 && RegExp.$4<256)
			return true;
	}	
	return false;
}		
function checkmac(mac)
{
	var re = /^([0-9a-fA-F]{2})(([/\s:-][0-9a-fA-F]{2}){5})$/ ;
	if(re.test(mac))
		return true;
	else
		return false;
}
jQuery(document).ready(function(){

jQuery("#ipmac_list").jqGrid({
    url:'sqldata.php',
	editurl:'ip_mac_edit.php',
    datatype: "json",
	colNames:['IP地址','mac地址', '备注', '可选'],
    colModel:[
		{name:'ip',index:'ip', width:100,align:"left",editable:true,editrules:{required:true}},    
   	//	{name:'ip',index:'ip', width:150,editable:true,edittype:"custom",editoptions:{custom_element: myelem, custom_value:myvalue},editrules:{required:true}},
   		{name:'mac',index:'mac', width:100,align:"left",editable:true,editrules:{required:true}},
   		{name:'memo',index:'memo', width:150, align:"left",editable:true,editrules:{required:true}},
   		//{name:'select',index:'select', width:100, align:"right",editable:true,editrules:{required:true}}
   		{name:'select',index:'select', width:30, align:"left",editable:true,edittype:"select",editoptions:{value:"1:是;0:否"}}	
   					
    ],
	width:650,
    pager: jQuery('#pager'),
    rowNum:50,
    rowList:[50,100],
    //imgpath: 'themes/basic/images',
 	multiselect:true,
    sortname: 'ip',
    viewrecords: true,
    sortorder: "asc",
    caption: "ip_mac绑定列表"
	});
	jQuery("#ipmac_list").jqGrid('navGrid','#pager',{edit:true,edittext:'编',add:true,addtext:'增',del:true,deltext:'删',search: false},
	{ 
	    afterShowForm:afterShowEdit,  
	  //  afterSubmit:processAddEdit, 
	    //beforeSubmit:validateData, 
	    closeAfterAdd: true, 
	    closeAfterEdit: true 
	},
	{
		beforeShowForm:afterShowADD,  
	  //  afterSubmit:processAddEdit, 
	    //beforeSubmit:validateData, 
		 beforeSubmit:function(postdata, formid)
		     {
		      if(checkip(postdata['ip'])==false)
   	          	  return[false,'IP地址不正确'];			
           
				if(checkmac(postdata['mac'])==false)
				{
					return[false,'Mac地址不正确'];
				}
				else
				{
					return [true];
				}
		},
	    closeAfterAdd: true, 
	    closeAfterEdit: true 
	},
	{
		onclickSubmit:function(postdata, formid) {
		var sr = jQuery("#ipmac_list").getGridParam('selrow');
        var rowData = jQuery("#ipmac_list").getRowData(sr);
   
      //   this.delData = {"id" : rowData['ip']};
      //   this.url ="ip_mac_edit.php";
         var retarr = {"ip" : rowData['ip']};
         return retarr; 
		}
	}
	);
});
function afterShowEdit(formId) {
	$("#ip",formId).attr('disabled','disabled'); 		
	//$("#tr_ids",formId).val('1000'); 		
	//$("#tr_ids",formId).hide(); 		
    // alert("edit");
    //do stuff after the form is rendered 
} 
function afterShowADD(formId) {
	$("#ip",formId).attr('disabled',''); 		
	//$("#tr_ids",formId).val('1000'); 		
	//$("#tr_ids",formId).hide(); 		
    // alert("edit");
    //do stuff after the form is rendered 	
} 
function afterShowdel(formId){
	  
 	
}
jQuery(document).ready(function() {scanipmac()});
function scanipmac()
{
	jQuery("#ipmac_scan").jqGrid({
	    url:'',
	    datatype: "json",
		colNames:['IP地址','mac地址', '备注'],
	    colModel:[
	   		{name:'ip',index:'ip', width:100,editable:true,editrules:{required:true}},
	   		{name:'mac',index:'mac', width:100,editable:true,editrules:{required:true}},
	   		{name:'memo',index:'memo', width:150, align:"left",editable:true,editrules:{required:true}}				
	    ],
        width:650,
	    rowNum:50,
	    rowList:[50,100],
	    //imgpath: 'themes/basic/images',
		multiselect:true,
	    sortname: 'ip',
	    viewrecords: true,
	    sortorder: "asc",
	    caption: "IpMac扫描"
	});
//	jQuery("#ipmac_scan").clearGridData();
//	jQuery("#ipmac_scan").setGridParam({url:'data.php?ip='+document.getElementById('ip').value}).trigger("reloadGrid");
}
jQuery(document).ready(function(){
	
	jQuery("#scan_new").click(function () 
			{
				if(document.getElementById('ipscan').value=="")
				{	
					alert("IP或IP段为空");
					return;
				}
				jQuery("#ipmac_scan").clearGridData();
				jQuery("#ipmac_scan").setGridParam({url:'data.php?ip='+document.getElementById('ipscan').value}).trigger("reloadGrid");
			});	
	});

jQuery(document).ready(function(){

	
	jQuery("#save_new").click(function () 
		{
			var dt = "";
			var dt_ip=[];
			var dt_mac=[];
			var dt_memo=[];
			var s = jQuery("#ipmac_scan").jqGrid('getGridParam','selarrrow');
			if(s.length)
			{
				for(var i=0;i<s.length;i++)
				{
					var myrow = jQuery('#ipmac_scan').jqGrid('getRowData',s[i]);
					//dt_ip  += "&ip="+myrow.ip;
					//dt_mac += "&mac="+myrow.mac;
					//dt_memo+= "&memo="+myrow.memo;
					dt_ip.push(myrow.ip);
					dt_mac.push(myrow.mac);
					dt_memo.push(myrow.memo); 
					//dt[i].ip = myrow.ip;
					//dt[i].mac = myrow.mac;
					//dt[i].memo = myrow.memo;
				}
			}
			if(dt_ip.length==0)
			{
				alert("没有选择数据");
				return;
			}		
			//window.location.href = "save_ip_mac.php?ip="+dt_ip+"&mac="+dt_mac+"&memo="+dt_memo;
			//window.location.href = "save_ip_mac.php?"+"ip="+dt_ip+"&mac="+dt_mac+"&memo="+dt_memo;
			
			function onDataReceived(json)
			{
				 alert(json.ip);				
			}
			$.ajax({
			    url: "save_ip_mac.php",
				cache:false,
				type:'POST',
			    method: 'POST',
			    dataType: 'json',
			    data:"ip="+dt_ip+"&mac="+dt_mac+"&memo="+dt_memo,
			    success: onDataReceived
			});
		});
});



</script>
</head>
<body>
<script>
/*function setipmacbind()
{
	var bind;
	if($("#isipmacopen").attr("checked")==true)
	{
		bind = 1;		
	}
	else
	{
		bind = 0;	
	}
	$.post("isipmacbind.php",{ipmac:bind});
}*/
</script>
<h1>已经保存的ip-mac对应</h1>
<table id="ipmac_list" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="pager" class="scroll" style="text-align: center;"></div>
<h1>扫描网络</h1>
<input type="text" id="ipscan" name="host" size="20"
	value="192.168.0.1/24" class="inputText_in"> <input id="scan_new"
	type=button value="扫描" class="inputButton_in" />
<h2>结果</h2>
<table id="ipmac_scan" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="pager1" class="scroll" style="text-align: center;"></div>
<h2>保存结果，可以刷新上面表格看结果</h2>
<input id="save_new" type=button value="保存" class="inputButton_in" /> <br>

</body>
</html>
