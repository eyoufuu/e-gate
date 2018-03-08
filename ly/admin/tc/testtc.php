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
   $sql_pro="select type,name from procat where proid=-1;";
   $result=$db->fetchRows($sql_pro);
   $sql_chid="select id from channel order by id";
   $chid=$db->query2one($sql_chid);
   $firstid=$chid['id'];
   
   $sql_updown="select upflow,downflow from globalpara";
   $updown=$db->query2one($sql_updown);
   $upflow=$updown['upflow'];
   $downflow=$updown['downflow'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>通道流量</title>
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

var symbol="Ip方式";

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

jQuery(document).ready(function(){
jQuery("#list_rules4").jqGrid({
	url:'rules_port_data.php?nd='+new Date().getTime(),
    editurl:'rules_port_edit.php',
    datatype: "json",
    colNames:['ID','通道ID','通道名称', '方式', '端口','描述'],
    colModel:[
  		{name:'id',index:'id', width:30,align:"right"},
		{name:'cid',index:'cid', width:150,hidden:true},
   		{name:'name',index:'name', width:150,hidden:true},
   		{name:'mode',index:'mode', width:100,hidden:true},
   		{name:'port',index:'pro', width:100, align:"right",editable:true,editrules:{required:true,number:true}},
        {name:'des',index:'des', width:100, align:"right",editable:true}				
    ],
    pager: jQuery('#pager1234'),
	width:450,
	 height:228,
    rowNum:20,
    rowList:[20,40],
	multiselect:true,
    sortname: 'id',
    viewrecords: true,
    sortorder: "asc",
    caption: "ip组列表",
	//hidegrid: true
	toolbar: [true,"top"],
	loadComplete:function(){
    }
	});
	
	
$("#t_list_rules4").append("<select size='1' id='select4' name='select'><option>Ip方式</option><option>Ip段方式</option><option>协议方式</option><option>端口方式</option></select>");
     $("#select4","#t_list_rules4").change(function(){
       var channelid = jQuery('#list_qos').jqGrid('getGridParam','selrow');
	   var sel=$(this).val();
	   var mode;
		if(sel == "Ip方式")
	      {	 
		   mode="0";
		   document.getElementById("channelnames").style.display=""; 
		   document.getElementById("channelnames2").style.display="none";
           document.getElementById("channelnames3").style.display="none";
		   document.getElementById("channelnames4").style.display="none";
		   jQuery("#select").val("Ip方式");
		   symbol="Ip方式";
		   jQuery("#list_rules").jqGrid('setGridParam',{url:"rules_data.php?q=1&mode="+mode+"&chanid="+channelid,page:1});
	       jQuery("#list_rules").jqGrid().trigger('reloadGrid');	
		  
		  }
        else if(sel == "Ip段方式")
		  {
		    mode="1";
 		    document.getElementById("channelnames").style.display="none";
            document.getElementById("channelnames2").style.display="";
            document.getElementById("channelnames3").style.display="none";
            document.getElementById("channelnames4").style.display="none";			
		    jQuery("#select2").val("Ip段方式");
		 	symbol="Ip段方式";
			jQuery("#list_rules2").jqGrid('setGridParam',{url:"rules_segg_data.php?q=1&mode="+mode+"&chanid="+channelid,page:1});
	        jQuery("#list_rules2").jqGrid().trigger('reloadGrid');	
		  }
		else if(sel == "协议方式")
          {
		    mode="2";
 		    document.getElementById("channelnames").style.display="none";
            document.getElementById("channelnames2").style.display="none";
            document.getElementById("channelnames3").style.display="";
            document.getElementById("channelnames4").style.display="none";			
		    jQuery("#select3").val("协议方式");
		 	symbol="协议方式";
			jQuery("#list_rules3").jqGrid('setGridParam',{url:"rules_pro_data.php?q=1&mode="+mode+"&chanid="+channelid,page:1});
	        jQuery("#list_rules3").jqGrid().trigger('reloadGrid');
		  }	
		else if(sel == "端口方式")
          {
		    mode="3";
 		    document.getElementById("channelnames").style.display="none";
            document.getElementById("channelnames2").style.display="none";
            document.getElementById("channelnames3").style.display="none";
            document.getElementById("channelnames4").style.display="";			
		    jQuery("#select4").val("端口方式");
		 	symbol="端口方式";
			jQuery("#list_rules4").jqGrid('setGridParam',{url:"rules_port_data.php?q=1&mode="+mode+"&chanid="+channelid,page:1});
	        jQuery("#list_rules4").jqGrid().trigger('reloadGrid');
		  }	
		  
        
				  
          		 		
      });  	
           	  

   jQuery("#list_rules4").jqGrid('navGrid','#pager1234',{edit:true,edittext:'编',add:true,addtext:'增', del:true, deltext:'删',search:false},
   { 
		closeAfterAdd: true, 
	    closeAfterEdit: true,
	    onclickSubmit:function(postdata, formid)
		{
			var sr = jQuery("#list_rules4").getGridParam('selrow');
	        var rowData = jQuery("#list_rules4").getRowData(sr);
             	       
		    var id = {"id" : rowData['id']};
	        return id; 
		}	 	
	},
	{
		closeAfterAdd: true, 
		closeAfterEdit: true, 
		onclickSubmit:function(postdata, formid)
		{
		  var sr = jQuery("#list_qos").getGridParam('selrow');
          var rowData = jQuery("#list_qos").getRowData(sr);
          var id = {"id" : rowData['id']};
          return id; 
		}   
	},
	{
		
	}
	
	);
		
});


</script>



<script type="text/javascript">

//var symbol="Ip方式";
jQuery(document).ready(function(){
jQuery("#list_rules3").jqGrid({
	url:'rules_pro_data.php?nd='+new Date().getTime(),
    editurl:'rules_pro_edit.php',
    datatype: "json",
    colNames:['ID','通道ID','通道名称', '方式', '协议名称','描述'],
    colModel:[
  		{name:'id',index:'id', width:30,align:"right"},
		{name:'cid',index:'cid', width:150,hidden:true},
   		{name:'name',index:'name', width:150,hidden:true},
   		{name:'mode',index:'mode', width:100,hidden:true},
   		{name:'pro',index:'pro', width:100, align:"right",editable: true,edittype:"select",editoptions:{value:"<?php  foreach($result as $row ) { echo  $row[type].":".$row[name].";" ;  } ?>"}},
        {name:'des',index:'des', width:100, align:"right",editable:true}				
    ],
    pager: jQuery('#pager123'),
	width:450,
	 height:228,
    rowNum:20,
    rowList:[20,40],
	multiselect:true,
    sortname: 'id',
    viewrecords: true,
    sortorder: "asc",
    caption: "ip组列表",
	//hidegrid: true
	toolbar: [true,"top"],
	loadComplete:function(){
    }
	});
	
	
$("#t_list_rules3").append("<select size='1' id='select3' name='select'><option>Ip方式</option><option>Ip段方式</option><option>协议方式</option><option>端口方式</option></select>");
     $("#select3","#t_list_rules3").change(function(){
       var channelid = jQuery('#list_qos').jqGrid('getGridParam','selrow');
	   var sel=$(this).val();
	   var mode;
		if(sel == "Ip方式")
	      {	 
		   mode="0";
		   document.getElementById("channelnames").style.display=""; 
		   document.getElementById("channelnames2").style.display="none";
           document.getElementById("channelnames3").style.display="none";
		     document.getElementById("channelnames4").style.display="none";	
		   jQuery("#select").val("Ip方式");
		   symbol="Ip方式";
		   jQuery("#list_rules").jqGrid('setGridParam',{url:"rules_data.php?q=1&mode="+mode+"&chanid="+channelid,page:1});
	       jQuery("#list_rules").jqGrid().trigger('reloadGrid');	
		  
		  }
        else if(sel == "Ip段方式")
		  {
		    mode="1";
 		    document.getElementById("channelnames").style.display="none";
            document.getElementById("channelnames2").style.display="";
            document.getElementById("channelnames3").style.display="none";	
              document.getElementById("channelnames4").style.display="none";				
		    jQuery("#select2").val("Ip段方式");
		 	symbol="Ip段方式";
			jQuery("#list_rules2").jqGrid('setGridParam',{url:"rules_segg_data.php?q=1&mode="+mode+"&chanid="+channelid,page:1});
	        jQuery("#list_rules2").jqGrid().trigger('reloadGrid');	
		  }
		else if(sel == "协议方式")
          {
		    mode="2";
 		    document.getElementById("channelnames").style.display="none";
            document.getElementById("channelnames2").style.display="none";
            document.getElementById("channelnames3").style.display="";	
              document.getElementById("channelnames4").style.display="none";				
		    jQuery("#select3").val("协议方式");
		 	symbol="协议方式";
			jQuery("#list_rules3").jqGrid('setGridParam',{url:"rules_pro_data.php?q=1&mode="+mode+"&chanid="+channelid,page:1});
	        jQuery("#list_rules3").jqGrid().trigger('reloadGrid');
		  }	
		 else if(sel == "端口方式")
          {
		    mode="3";
 		    document.getElementById("channelnames").style.display="none";
            document.getElementById("channelnames2").style.display="none";
            document.getElementById("channelnames3").style.display="none";	
              document.getElementById("channelnames4").style.display="";				
		    jQuery("#select4").val("端口方式");
		 	symbol="端口方式";
			jQuery("#list_rules4").jqGrid('setGridParam',{url:"rules_port_data.php?q=1&mode="+mode+"&chanid="+channelid,page:1});
	        jQuery("#list_rules4").jqGrid().trigger('reloadGrid');
		  }	
        
				  
          		 		
      });  	
           	  

   jQuery("#list_rules3").jqGrid('navGrid','#pager123',{edit:true,edittext:'编',add:true,addtext:'增', del:true, deltext:'删',search:false},
   { 
		closeAfterAdd: true, 
	    closeAfterEdit: true,
	    onclickSubmit:function(postdata, formid)
		{
			var sr = jQuery("#list_rules3").getGridParam('selrow');
	        var rowData = jQuery("#list_rules3").getRowData(sr);
             	       
		    var id = {"id" : rowData['id']};
	        return id; 
		}	 	
	},
	{
		closeAfterAdd: true, 
		closeAfterEdit: true, 
		onclickSubmit:function(postdata, formid)
		{
		  var sr = jQuery("#list_qos").getGridParam('selrow');
          var rowData = jQuery("#list_qos").getRowData(sr);
          var id = {"id" : rowData['id']};
          return id; 
		}   
	},
	{
		
	}
	
	);
		
});


</script>



<script type="text/javascript">

jQuery(document).ready(function(){
jQuery("#list_rules2").jqGrid({
	url:'rules_segg_data.php?nd='+new Date().getTime(),
    //url:'data.php?nd='+new Date().getTime(),
	editurl:'rules_segg_edit.php',
    datatype: "json",
    colNames:['ID','通道ID','通道名称', '方式', '开始ip','结束ip','描述'],
    colModel:[
  		{name:'id',index:'id', width:30,align:"right"},
		{name:'cid',index:'cid', width:150,hidden:true},
   		{name:'name',index:'name', width:150,hidden:true},
   		{name:'mode',index:'mode', width:100,hidden:true},
   		{name:'ips',index:'ips', width:100, align:"right",editable:true,editrules:{required:true}},
        {name:'ipe',index:'ipe', width:100, align:"right",editable:true,editrules:{required:true}},
        {name:'des',index:'des', width:100, align:"right",editable:true}				
    ],
    pager: jQuery('#pager12'),
	width:450,
	height:228,
    rowNum:20,
    rowList:[20,40],
	multiselect:true,
    sortname: 'id',
    viewrecords: true,
    sortorder: "asc",
    caption: "ip组列表",
	//hidegrid: true
	toolbar: [true,"top"],
	loadComplete:function(){
    }
	
	});
	
	
$("#t_list_rules2").append("<select size='1' id='select2' name='select'><option>Ip方式</option><option>Ip段方式</option><option>协议方式</option><option>端口方式</option></select>");
     $("#select2","#t_list_rules2").change(function(){
       var channelid = jQuery('#list_qos').jqGrid('getGridParam','selrow');
	   var sel=$(this).val();
	   var mode;
		if(sel == "Ip方式")
	      {	 
		   mode="0";
		   document.getElementById("channelnames").style.display="";
           document.getElementById("channelnames2").style.display="none";
		   document.getElementById("channelnames3").style.display="none";
		   document.getElementById("channelnames4").style.display="none";
		   jQuery("#select").val("Ip方式");
		   symbol="Ip方式";
		   jQuery("#list_rules").jqGrid('setGridParam',{url:"rules_data.php?q=1&mode="+mode+"&chanid="+channelid,page:1});
	       jQuery("#list_rules").jqGrid().trigger('reloadGrid');			  
          
		  
		  }
        else if(sel == "Ip段方式")
		  {
		    mode="1";
 		    document.getElementById("channelnames").style.display="none";
            document.getElementById("channelnames2").style.display=""; 
			document.getElementById("channelnames3").style.display="none"; 
			document.getElementById("channelnames4").style.display="none";
		    jQuery("#select2").val("Ip段方式");
		 	symbol="Ip段方式";
			jQuery("#list_rules2").jqGrid('setGridParam',{url:"rules_segg_data.php?q=1&mode="+mode+"&chanid="+channelid,page:1});
	        jQuery("#list_rules2").jqGrid().trigger('reloadGrid');			  
		  }
		else if(sel == "协议方式")
          {
		    mode="2";
		    document.getElementById("channelnames").style.display="none";
            document.getElementById("channelnames2").style.display="none"; 
			document.getElementById("channelnames3").style.display=""; 
			document.getElementById("channelnames4").style.display="none";	
		    jQuery("#select3").val("协议方式");
		 	symbol="协议方式";
			jQuery("#list_rules3").jqGrid('setGridParam',{url:"rules_pro_data.php?q=1&mode="+mode+"&chanid="+channelid,page:1});
	        jQuery("#list_rules3").jqGrid().trigger('reloadGrid');		
			
			}	
		  else if(sel == "端口方式")
          {
		    mode="3";
 		    document.getElementById("channelnames").style.display="none";
            document.getElementById("channelnames2").style.display="none";
            document.getElementById("channelnames3").style.display="none";	
            document.getElementById("channelnames4").style.display="";				
		    jQuery("#select4").val("端口方式");
		 	symbol="端口方式";
			jQuery("#list_rules4").jqGrid('setGridParam',{url:"rules_port_data.php?q=1&mode="+mode+"&chanid="+channelid,page:1});
	        jQuery("#list_rules4").jqGrid().trigger('reloadGrid');
		  }	
                 
		 			  
          		 		
      });  	
           	  

   jQuery("#list_rules2").jqGrid('navGrid','#pager12',{edit:true,edittext:'编',add:true,addtext:'增', del:true, deltext:'删',search:false},
   { 
		closeAfterAdd: true, 
	    closeAfterEdit: true,
		beforeSubmit:function(postdata, formid)
		        {
		          	if((checkip(postdata['ips'])==false) || (checkip(postdata['ipe'])==false) )
   	          	     return[false,'IP地址不正确'];
		            return [true,'ok'];
		        },
	    onclickSubmit:function(postdata, formid)
		{
			var sr = jQuery("#list_rules2").getGridParam('selrow');
	        var rowData = jQuery("#list_rules2").getRowData(sr);
             	       
		    var id = {"id" : rowData['id']};
	        return id; 
		}	 	
	},
	{
		closeAfterAdd: true, 
		closeAfterEdit: true, 
		beforeSubmit:function(postdata, formid)
		        {
		          	if((checkip(postdata['ips'])==false) || (checkip(postdata['ipe'])==false) )
   	          	     return[false,'IP地址不正确'];
		            return [true,'ok'];
		        },
		onclickSubmit:function(postdata, formid)
		{
		  var sr = jQuery("#list_qos").getGridParam('selrow');
          var rowData = jQuery("#list_qos").getRowData(sr);
          var id = {"id" : rowData['id']};
          return id; 
		}   
	},
	{
		
	}
	
	); 
		
});


</script>



<script type="text/javascript">

jQuery(document).ready(function(){
jQuery("#list_rules").jqGrid({
	url:'rules_data.php?nd='+new Date().getTime(),
    //url:'data.php?nd='+new Date().getTime(),
	editurl:'rules_edit.php',
    datatype: "json",
	   colNames:['ID','通道ID','通道名称', '方式', 'ip','描述'],
    colModel:[
  		{name:'id',index:'id', width:30,align:"right"},
		{name:'cid',index:'cid', width:150,hidden:true},
   		{name:'name',index:'name', width:150,hidden:true},
   		{name:'mode',index:'mode', width:100,hidden:true},
   		{name:'ip',index:'ip', width:100, align:"right",editable:true,editrules:{required:true}},
        {name:'des',index:'des', width:100, align:"right",editable:true}				
    ],
    pager: jQuery('#pager1'),
	 width:450,
	 height:228,
    rowNum:20,
    rowList:[20,40],
	multiselect:true,
    sortname: 'id',
    viewrecords: true,
    sortorder: "asc",
    caption: "ip组列表",
	//hidegrid: true
	toolbar: [true,"top"],
	loadComplete:function(){
	
	}
	});
	

$("#t_list_rules").append("<select size='1' id='select' name='select'><option>Ip方式</option><option>Ip段方式</option><option>协议方式</option><option>端口方式</option></select>");
     $("#select","#t_list_rules").change(function(){
       var channelid = jQuery('#list_qos').jqGrid('getGridParam','selrow'); 
	   var sel=$(this).val();
	   var mode;
		if(sel == "Ip方式")
	      {	 
		   mode="0";
		   document.getElementById("channelnames").style.display="";
           document.getElementById("channelnames2").style.display="none";
		   document.getElementById("channelnames3").style.display="none";
		   document.getElementById("channelnames4").style.display="none";				
		   jQuery("#select").val("Ip方式");
		   symbol="Ip方式";
		   jQuery("#list_rules").jqGrid('setGridParam',{url:"rules_data.php?q=1&mode="+mode+"&chanid="+channelid,page:1});
	       jQuery("#list_rules").jqGrid().trigger('reloadGrid');			  
		   
		  }
        else if(sel == "Ip段方式")
		  {
		    mode="1";
			document.getElementById("channelnames").style.display="none";
            document.getElementById("channelnames2").style.display=""; 
			document.getElementById("channelnames3").style.display="none"; 
			document.getElementById("channelnames4").style.display="none";				
 		    jQuery("#select2").val("Ip段方式");
			symbol="Ip段方式";
			jQuery("#list_rules2").jqGrid('setGridParam',{url:"rules_segg_data.php?q=1&mode="+mode+"&chanid="+channelid,page:1});
	        jQuery("#list_rules2").jqGrid().trigger('reloadGrid');			    

		  }
		else if(sel == "协议方式")
          {
		    mode="2";
			document.getElementById("channelnames").style.display="none";
            document.getElementById("channelnames2").style.display="none"; 
			document.getElementById("channelnames3").style.display=""; 
			document.getElementById("channelnames4").style.display="none";				
 		    jQuery("#select3").val("协议方式");
			  symbol="协议方式";
			jQuery("#list_rules3").jqGrid('setGridParam',{url:"rules_pro_data.php?q=1&mode="+mode+"&chanid="+channelid,page:1});
	        jQuery("#list_rules3").jqGrid().trigger('reloadGrid');			    
          }	
		   else if(sel == "端口方式")
          {
		    mode="3";
 		    document.getElementById("channelnames").style.display="none";
            document.getElementById("channelnames2").style.display="none";
            document.getElementById("channelnames3").style.display="none";	
            document.getElementById("channelnames4").style.display="";				
		    jQuery("#select4").val("端口方式");
		 	symbol="端口方式";
			jQuery("#list_rules4").jqGrid('setGridParam',{url:"rules_port_data.php?q=1&mode="+mode+"&chanid="+channelid,page:1});
	        jQuery("#list_rules4").jqGrid().trigger('reloadGrid');
		  }	
		 
        
          		 		
      });  	
           	  

   jQuery("#list_rules").jqGrid('navGrid','#pager1',{edit:true,edittext:'编',add:true,addtext:'增', del:true, deltext:'删',search:false},
    { 
		closeAfterAdd: true, 
	    closeAfterEdit: true,
		beforeSubmit:function(postdata, formid)
		       {
		          if(checkip(postdata['ip'])==false) 
			       return[false,'IP地址不正确'];
			       return [true,'ok'];
		       }, 
	    onclickSubmit:function(postdata, formid)
		{
			var sr = jQuery("#list_rules").getGridParam('selrow');
	        var rowData = jQuery("#list_rules").getRowData(sr);
             	       
		    var id = {"id" : rowData['id']};
	        return id; 
		}	 	
	},
	{
		closeAfterAdd: true, 
		closeAfterEdit: true, 
		beforeSubmit:function(postdata, formid)
		       {
		          if(checkip(postdata['ip'])==false) 
			       return[false,'IP地址不正确'];
			       return [true,'ok'];
		       }, 
		onclickSubmit:function(postdata, formid)
		{
		  var sr = jQuery("#list_qos").getGridParam('selrow');
          var rowData = jQuery("#list_qos").getRowData(sr);
          var id = {"id" : rowData['id']};
          return id; 
		}   
	},
	{
		
	}
	
	);   
		
});


</script>

<script type="text/javascript">
jQuery(document).ready(function(){
jQuery("#list_qos").jqGrid({
    url:'channel_data.php?nd='+new Date().getTime(),
	editurl:'channel_data_edit.php',
    datatype: "json",
    colNames:['编号','通道名称','上行带宽(KB)', '下行带宽(KB)', '优先级'],
    colModel:[
  		{name:'id',index:'id', width:40,align:"right"},
  		{name:'name',index:'name', width:150,align:"center",editable:true,editrules:{required:true}},
   		{name:'uprate',index:'uprate', width:100,align:"right",editable:true,editrules:{required:true,number:true}},
   		{name:'downrate',index:'downrate', width:100,align:"right",editable:true,editrules:{required:true,number:true}},
   		{name:'priority',index:'priority', width:100, align:"right",editable: true,edittype:"select",editoptions:{value:"1:高;2:中;3:低"}}
		
    ],
    pager: jQuery('#pager'),
	 width:500,
	 height:250,
    rowNum:20,
    rowList:[20,40],
    sortname: 'id',
    viewrecords: true,
    sortorder: "asc",
	beforeRequest:function(){
	},
	
	gridComplete: function(){
		
		 //var ids = jQuery("#list_qos").jqGrid('getDataIDs');
		//for(var i=0;i<ids.length;i++){
		// var cl = ids[i];
	    //var ret = jQuery("#list_qos").jqGrid('getRowData',cl);
	    //alert(ret.name);
        //	ge = "<a href ='tc_advance_road_rule.php?channelname="+ret.name+"&channelid=" +ret.ids+"'>" +"编辑规则"+"</a>";  
		//	jQuery("#list_qos").jqGrid('setRowData',ids[i],{act:ge});
		//}	
	},
	
	loadComplete:function(){
		jQuery("#list_qos").jqGrid('setSelection',<?php if(isset($firstid)) echo $firstid; else echo "1"; ?>);
    },
	onSelectRow: function(id) {
	    //alert(jQuery("#select").val());
		if(id != null) 
		{
			var ret = jQuery("#list_qos").jqGrid('getRowData',id);
			//var mode = jQuery("#select").val(); 	
			//alert(mode);
			var modes;
			if(symbol == "Ip方式")
	         {	
              		 
              modes="0";
			  jQuery("#list_rules").jqGrid('setGridParam',{url:"rules_data.php?channelid="+ret.id+"&modes="+modes,page:1});
			  jQuery("#list_rules").jqGrid('setCaption',ret.name+"的详细规则显示").trigger('reloadGrid');	
		     }
           else if(symbol == "Ip段方式")
		    {
			  
 		      modes="1";
			  jQuery("#list_rules2").jqGrid('setGridParam',{url:"rules_segg_data.php?channelid="+ret.id+"&modes="+modes,page:1});
			  jQuery("#list_rules2").jqGrid('setCaption',ret.name+"的详细规则显示").trigger('reloadGrid');		
			}
		   else if(symbol == "协议方式")
            {
		       modes="2";
			  jQuery("#list_rules3").jqGrid('setGridParam',{url:"rules_pro_data.php?channelid="+ret.id+"&modes="+modes,page:1});
			  jQuery("#list_rules3").jqGrid('setCaption',ret.name+"的详细规则显示").trigger('reloadGrid');		
		    }	
		   else if(symbol == "端口方式")
            {
		       modes="3";
			  jQuery("#list_rules4").jqGrid('setGridParam',{url:"rules_port_data.php?channelid="+ret.id+"&modes="+modes,page:1});
			  jQuery("#list_rules4").jqGrid('setCaption',ret.name+"的详细规则显示").trigger('reloadGrid');		
		    }		
		 
		}
	},

    caption: "通道明细"
});
  jQuery("#list_qos").jqGrid('navGrid','#pager',{edit:true,edittext:'编',add:true,addtext:'增', del:true, deltext:'删',search:false},
	{ 
		//closeAfterAdd: true, 
	    //closeAfterEdit: true,
	    //onclickSubmit:function(postdata, formid)
		//{
	    //		var sr = jQuery("#list_rules2").getGridParam('selrow');
	     //   var rowData = jQuery("#list_rules2").getRowData(sr);
             	       
		 //   var id = {"id" : rowData['id']};
	     //   return id; 
		//}	 	
	},
	{
		//closeAfterAdd: true, 
		//closeAfterEdit: true, 
		beforeSubmit:function(postdata, formid)
		{
		   
		   
		   
		   var up = parseInt(jQuery("#upaccount").val());
		   var down = parseInt(jQuery("#downaccount").val());
		    var rowdata = jQuery("#list_qos").getRowData();
			var upcount=0;
			var downcount=0;
			for(var i=0; i<rowdata.length; i++)
			 {
			  upcount+=(parseInt(rowdata[i]['uprate']));
			  downcount+=(parseInt(rowdata[i]['downrate']));
			 }
			 upcount+=parseInt(postdata['uprate']);
			 downcount+=parseInt(postdata['downrate']);
		    if(upcount > up)	
           	  return [false,"超过了总的上行流量，请重新设置"]; 
		    else if(downcount > down)	
           	  return [false,"超过了总的下行流量，请重新设置"];
            else
               return [true];			
		}  
	},
	{
	  afterSubmit:function(postdata, formid)
		{
		  jQuery("#list_rules").jqGrid().trigger('reloadGrid');
		  jQuery("#list_rules2").jqGrid().trigger('reloadGrid');
		  jQuery("#list_rules3").jqGrid().trigger('reloadGrid');
		  jQuery("#list_rules4").jqGrid().trigger('reloadGrid');
		  return [true];
		}   	 
	}
	
	);	

});

</script>
<style type="text/css">


#one{float:left;}
#two{float:left;}

</style

</head>
<body>
  <h1>高级流量控制</h1>

   <table>
		<tr>
		  <td> QOS启用:</td> 
		  <td align ='left' ><input type="checkbox" id="switch_qos" value="switch_qos" /></td>
		<tr/>
   </table> 
<h1>总带宽</h1>

  <table>
		<tr>
		  <td> 上行总带宽：</td> 
		  <td align ='left' ><input id="upaccount" type="text" value="<?php echo $upflow; ?>" /></td>
		  <td align ='left' >KB</td>
		</tr>
	    <tr>
		  <td> 下行总带宽：</td>
		  <td align ='left'><input  id="downaccount" type="text" value="<?php echo $downflow; ?>" /></td>
		   <td align ='left' >KB</td>
		 </tr>
   </table> 
<h1>QOS明细</h1>
<div id="one"> 
    <table id="list_qos" class="scroll" cellpadding="0" cellspacing="0"></table>
    <div id="pager" class="scroll" style="text-align:center;"></div>
</div>

<div id="two">
      <div id = "channelnames">
         <table id="list_rules" class="scroll" cellpadding="0" cellspacing="0"></table>
         <div id="pager1" class="scroll" style="text-align:center;"></div>
     </div>

    <div id = "channelnames2" style="display:none">
       <table id="list_rules2" class="scroll" cellpadding="0" cellspacing="0"></table>
       <div id="pager12" class="scroll" style="text-align:center;"></div>
    </div>

    <div id = "channelnames3" style="display:none">
      <table id="list_rules3" class="scroll" cellpadding="0" cellspacing="0"></table>
      <div id="pager123" class="scroll" style="text-align:center;"></div>
    </div>
   <div id = "channelnames4" style="display:none">
      <table id="list_rules4" class="scroll" cellpadding="0" cellspacing="0"></table>
      <div id="pager1234" class="scroll" style="text-align:center;"></div>
  </div>
</div>

</body>
</html>