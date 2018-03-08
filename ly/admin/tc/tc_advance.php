<?php

  require_once('_inc.php');
  
   $sql_chid="select id, name from channel order by id";
   $chname=$db->fetchRows($sql_chid);
  
   $sql_updown="select * from globalpara";
   $updown=$db->query2one($sql_updown);
   $upflow=$updown['upbw']/8;
   $downflow=$updown['downbw']/8;
   $percentupbw=$updown['percentupbw'];
   $percentdownbw=$updown['percentupbw'];
   $ack = $updown['ack']; 
   $icmp = $updown['icmp'];
   $isqos = $updown['isqosopen'];
   $ceil = $updown['ceil'];
   
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
var upbw_t=<?php
  $uptemp = json_encode(floor($upflow*$percentupbw/100));
	          echo $uptemp;               
  ?>;
var downbw_t=<?php
  $downtemp = json_encode(floor($downflow*$percentdownbw/100));
	          echo $downtemp;               
  ?>;

  
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
 function longtoip(ip)
			{  
			   var iparray = ip.split(".");
               var ipstr = iparray[0]+iparray[1]+iparray[2]+iparray[3]
			   //var s1 = ip&0xff;
			   //var s2 = (ip&0xffff)>>8;
			   //var s3 = (ip>>16)&0xff;
			   //var s4 = ip>>>24;
			   //var sip = s1+"."+s2+"."+s3+"."+s4;
			   return ipstr;
			}		

jQuery(document).ready(function(){
jQuery("#list_rules4").jqGrid({
	url:'rules_port_data.php?nd='+new Date().getTime(),
    editurl:'rules_port_edit.php',
    datatype: "json",
    colNames:['ID','通道ID','通道名称', '方式', '端口','描述'],
    colModel:[
  		{name:'id',index:'id', width:5,align:"right"},
		{name:'cid',index:'cid', width:30,hidden:true},
   		{name:'name',index:'name', width:30,hidden:true},
   		{name:'mode',index:'mode', width:30,hidden:true},
   		{name:'port',index:'pro', width:20, align:"right",editable:true,editrules:{required:true,number:true}},
        {name:'des',index:'des', width:20, align:"left",editable:true}				
    ],
    pager: jQuery('#pager1234'),
	width:500,
	height:228,
    //rowNum:20,
    //rowList:[20,40],
	multiselect:false,
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
	   var ret = jQuery("#list_qos").jqGrid('getRowData',channelid);
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
	       jQuery("#list_rules").jqGrid('setCaption',ret.name+"的详细规则显示").trigger('reloadGrid');	
		  
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
	        jQuery("#list_rules2").jqGrid('setCaption',ret.name+"的详细规则显示").trigger('reloadGrid');	
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
	        jQuery("#list_rules3").jqGrid('setCaption',ret.name+"的详细规则显示").trigger('reloadGrid');
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
	        jQuery("#list_rules4").jqGrid('setCaption',ret.name+"的详细规则显示").trigger('reloadGrid');
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
		},
         afterSubmit:function(response,postdata) 
		 {
		        var success = true; 
                var message = "" 
                var json = eval('(' + response.responseText + ')'); 
				//alert(json.message);
                var new_id = "1"; 
                return [json.success,json.message,new_id]; 
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
		},
         afterSubmit:function(response,postdata) 
		 {
		        var success = true; 
                var message = "" 
                var json = eval('(' + response.responseText + ')'); 
				//alert(json.message);
                var new_id = "1"; 
                return [json.success,json.message,new_id]; 
		 } 				
	},
	{
		
	}
	
	);
		
});


</script>



<script type="text/javascript">

var pronamearray = <?php
       $sql_pro="select type,name from procat where proid=-1;";
       $result=$db->fetchRows($sql_pro);
		$return_array = array();
        $i=0;
	  foreach($result as $row){
		 $return_array[$i][0]=$row['type'];
		 $return_array[$i][1]=$row['name'];
		 $i++;
	  }
	  
	  $tarray = json_encode($return_array);
	  echo $tarray;
   ?>;
   var pronamestr="";
   var i=0; 
  for (i=0;i<pronamearray.length;i++)
  {
    if(i == pronamearray.length-1)
     {
	  pronamestr=pronamestr+pronamearray[i][0] +":"+ pronamearray[i][1];
	  break;
	 } 
    pronamestr=pronamestr+pronamearray[i][0] +":"+ pronamearray[i][1]+";"
  }
 

jQuery(document).ready(function(){
jQuery("#list_rules3").jqGrid({
	url:'rules_pro_data.php?nd='+new Date().getTime(),
    editurl:'rules_pro_edit.php',
    datatype: "json",
    colNames:['ID','通道ID','通道名称', '方式', '协议名称','描述'],
    colModel:[
  		{name:'id',index:'id', width:5,align:"right"},
		{name:'cid',index:'cid', width:30,hidden:true},
   		{name:'name',index:'name', width:30,hidden:true},
   		{name:'mode',index:'mode', width:30,hidden:true},
   		{name:'pro',index:'pro', width:20, align:"left",editable: true,edittype:"select",editoptions:{value:pronamestr}},
        {name:'des',index:'des', width:20, align:"left",editable:true}				
    ],
    pager: jQuery('#pager123'),
	width:500,
	 height:228,
    //rowNum:20,
    //rowList:[20,40],
	multiselect:false,
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
	   var ret = jQuery("#list_qos").jqGrid('getRowData',channelid);
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
	       jQuery("#list_rules").jqGrid('setCaption',ret.name+"的详细规则显示").trigger('reloadGrid');	
		  
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
	        jQuery("#list_rules2").jqGrid('setCaption',ret.name+"的详细规则显示").trigger('reloadGrid');	
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
	        jQuery("#list_rules3").jqGrid('setCaption',ret.name+"的详细规则显示").trigger('reloadGrid');
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
	        jQuery("#list_rules4").jqGrid('setCaption',ret.name+"的详细规则显示").trigger('reloadGrid');
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
		},
       afterSubmit:function(response,postdata) 
		 {
		        var success = true; 
                var message = "" 
                var json = eval('(' + response.responseText + ')'); 
				//alert(json.message);
                var new_id = "1"; 
                return [json.success,json.message,new_id]; 
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
		},
       afterSubmit:function(response,postdata) 
		 {
		        var success = true; 
                var message = "" 
                var json = eval('(' + response.responseText + ')'); 
				//alert(json.message);
                var new_id = "1"; 
                return [json.success,json.message,new_id]; 
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
  		{name:'id',index:'id', width:5,align:"right"},
		{name:'cid',index:'cid', width:30,hidden:true},
   		{name:'name',index:'name', width:30,hidden:true},
   		{name:'mode',index:'mode', width:30,hidden:true},
   		{name:'ips',index:'ips', width:20, align:"left",editable:true,editrules:{required:true}},
        {name:'ipe',index:'ipe', width:20, align:"left",editable:true,editrules:{required:true}},
        {name:'des',index:'des', width:20, align:"left",editable:true}				
    ],
    pager: jQuery('#pager12'),
	width:500,
	height:228,
    //rowNum:20,
    //rowList:[20,40],
	multiselect:false,
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
	   var ret = jQuery("#list_qos").jqGrid('getRowData',channelid);
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
	       jQuery("#list_rules").jqGrid('setCaption',ret.name+"的详细规则显示").trigger('reloadGrid');			  
          
		  
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
	        jQuery("#list_rules2").jqGrid('setCaption',ret.name+"的详细规则显示").trigger('reloadGrid');			  
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
	        jQuery("#list_rules3").jqGrid('setCaption',ret.name+"的详细规则显示").trigger('reloadGrid');		
			
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
	        jQuery("#list_rules4").jqGrid('setCaption',ret.name+"的详细规则显示").trigger('reloadGrid');
		  }	
                 
		 			  
          		 		
      });  	
           	  

   jQuery("#list_rules2").jqGrid('navGrid','#pager12',{edit:true,edittext:'编',add:true,addtext:'增', del:true, deltext:'删',search:false},
   { 
		closeAfterAdd: true, 
	    closeAfterEdit: true,
		//afterSubmit:processAddEdit,    	 	
		beforeSubmit:function(postdata, formid)
		        {
		          	
                    					
					if((checkip(postdata['ips'])==false) || (checkip(postdata['ipe'])==false) )
   	          	     return[false,'IP地址不正确'];
					else if( postdata['ips'] == postdata['ipe']) 
					 return[false, 'Ip段方式:开始ip和结束ip不能相同'];
					
					
		            return [true,'ok'];
		        },
	    onclickSubmit:function(postdata, formid)
		{
			var sr = jQuery("#list_rules2").getGridParam('selrow');
	        var rowData = jQuery("#list_rules2").getRowData(sr);
             	       
		    var id = {"id" : rowData['id']};
	        return id; 
		},
		 afterSubmit:function(response,postdata) 
		 {
		        var success = true; 
                var message = "" 
                var json = eval('(' + response.responseText + ')'); 
				//alert(json.message);
                var new_id = "1"; 
                return [json.success,json.message,new_id]; 
		 }
       
	},
	{
		closeAfterAdd: true, 
		closeAfterEdit: true, 
		//afterSubmit:processAddEdit,
		beforeSubmit:function(postdata, formid)
		        {
		          	if((checkip(postdata['ips'])==false) || (checkip(postdata['ipe'])==false) )
   	          	     return[false,'IP地址不正确'];
					 else if( postdata['ips'] == postdata['ipe']) 
					 return[false, 'Ip段方式:开始ip和结束ip不能相同'];
					 //var ipsint = longtoip(postdata['ips']);
					 //var ipeint = longtoip(postdata['ipe']);
					 //alert(parseInt(ipeint)-parseInt(ipsint));
					 //if((parseInt(ipeint)-parseInt(ipsint)) > 254)
					 //var ipeint = ;
					 //if(ips3 == ipe3)
                     //return[false, 'Ip段方式:ip段内最多容纳256台主机'];  					 
					 
		            return [true,'ok'];
		        },
		onclickSubmit:function(postdata, formid)
		{
		  var sr = jQuery("#list_qos").getGridParam('selrow');
          var rowData = jQuery("#list_qos").getRowData(sr);
          var id = {"id" : rowData['id']};
          return id; 
		},
        afterSubmit:function(response,postdata) 
		 {
		        var success = true; 
                var message = "" 
                var json = eval('(' + response.responseText + ')'); 
				//alert(json.message);
                var new_id = "1"; 
                return [json.success,json.message,new_id]; 
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
  		{name:'id',index:'id', width:5,align:"right"},
		{name:'cid',index:'cid', width:30,hidden:true},
   		{name:'name',index:'name', width:30,hidden:true},
   		{name:'mode',index:'mode', width:30,hidden:true},
   		{name:'ip',index:'ip', width:20, align:"left",editable:true,editrules:{required:true}},
        {name:'des',index:'des', width:20, align:"left",editable:true}				
    ],
    pager: jQuery('#pager1'),
	 width:500,
	 height:228,
    //rowNum:20,
    //rowList:[20,40],
	multiselect:false,
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
	   var ret = jQuery("#list_qos").jqGrid('getRowData',channelid);
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
	       jQuery("#list_rules").jqGrid('setCaption',ret.name+"的详细规则显示").trigger('reloadGrid');			  
		   
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
	        jQuery("#list_rules2").jqGrid('setCaption',ret.name+"的详细规则显示").trigger('reloadGrid');			    

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
	        jQuery("#list_rules3").jqGrid('setCaption',ret.name+"的详细规则显示").trigger('reloadGrid');			    
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
	        jQuery("#list_rules4").jqGrid('setCaption',ret.name+"的详细规则显示").trigger('reloadGrid');
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
		},
          afterSubmit:processAddEdit	 	
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
		},
		
	   afterSubmit:processAddEdit
       
	},
	{
		
	}
	
	);  
 function processAddEdit(response, postdata) { 
                
				
				//alert(response);
				var success = true; 
                var message = "" 
                var json = eval('(' + response.responseText + ')'); 
				//alert(json.message);
                var new_id = "1"; 
                return [json.success,json.message,new_id]; 
        }; 	
		
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
  		{name:'id',index:'id', width:20,align:"right"},
  		{name:'name',index:'name', width:100,align:"left",editable:true,editrules:{required:true}},
   		{name:'uprate',index:'uprate', width:50,align:"right",editable:true,editrules:{required:true,number:true}},
   		{name:'downrate',index:'downrate', width:50,align:"right",editable:true,editrules:{required:true,number:true}},
   		{name:'priority',index:'priority', width:40, align:"left",editable: true,edittype:"select",editoptions:{value:"1:高;2:中;3:低"}}
		
    ],
    pager: jQuery('#pager'),
	width:500,
	height:250,
   //rowNum:20,
    //rowList:[20,40],
    sortname: 'id',
    viewrecords: true,
    sortorder: "asc",
	beforeRequest:function(){
	},
	
	gridComplete: function(){
		
      		
	},
	
	loadComplete:function(){
	    var temp = jQuery("#list_qos").getRowData();
       if(temp != "")	
	   {
		var firstid = temp[0]['id'];
        jQuery("#list_qos").jqGrid('setSelection',firstid);
		jQuery("#mySelect").empty(); 
        jQuery("#Select").empty(); 		
		 var ack_chid = <?php 
      
           
         	$acktemp = json_encode($ack);
	        echo $acktemp;                 
  
             ?>;
	     var icmp_chid = <?php
		 
		      $icmptemp = json_encode($icmp);
	          echo $icmptemp;                 
		    ?>;		 
	    
		jQuery("#mySelect").append("<option value='0'>默认</option>");
        jQuery("#Select").append("<option value='0'>默认</option>");	
		for(var i=0;i<temp.length;i++)
		 {  
		   if((temp[i]['id'] == ack_chid) && (temp[i]['id'] == icmp_chid) )
		   {
		     jQuery("#Select").append("<option value='"+temp[i]['id']+"'  selected=\"selected\">"+temp[i]['name']+"</option>");		   
		     jQuery("#mySelect").append("<option value='"+temp[i]['id']+"' selected=\"selected\">"+temp[i]['name']+"</option>");
		   }
		   else if(temp[i]['id'] == icmp_chid)
		   {
		     
             jQuery("#mySelect").append("<option value='"+temp[i]['id']+"' selected=\"selected\">"+temp[i]['name']+"</option>");
		     jQuery("#Select").append("<option value='"+temp[i]['id']+"'>"+temp[i]['name']+"</option>");	
		   }
		  else if(temp[i]['id'] == ack_chid)
		   {
		     
              jQuery("#mySelect").append("<option value='"+temp[i]['id']+"'>"+temp[i]['name']+"</option>");
		      jQuery("#Select").append("<option value='"+temp[i]['id']+"'  selected=\"selected\">"+temp[i]['name']+"</option>");		   
		   }
		  else
		   {
		     jQuery("#mySelect").append("<option value='"+temp[i]['id']+"'>"+temp[i]['name']+"</option>");
             jQuery("#Select").append("<option value='"+temp[i]['id']+"'>"+temp[i]['name']+"</option>");	
		   }
		}
		
	   };
	  
   	  
	    
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
	  afterShowForm:function ( ) {
	    var channelid = jQuery('#list_qos').jqGrid('getGridParam','selrow'); 
	    var ret = jQuery("#list_qos").jqGrid('getRowData',channelid);
         var rowdata = jQuery("#list_qos").getRowData();  
         var upcount = 0;
         var downcount = 0;		 
		  if(rowdata == "")
             {		   
    		    $("#uprate").val("可分配带宽"+parseInt(upbw_t)+"KB");
                $("#downrate").val("可分配带宽"+parseInt(downbw_t)+"KB");  				
			 }
          else
            {
			   for(var i=0; i<rowdata.length; i++)
			 {
			   upcount+=(parseInt(rowdata[i]['uprate']));
			   downcount+=(parseInt(rowdata[i]['downrate']));
			 }
			 
			 //alert(upcount);
			 //alert(downcount);
			 $("#uprate").val("可分配带宽"+parseInt(parseInt(upbw_t)-upcount+parseInt(ret.uprate))+"KB");
             $("#downrate").val("可分配带宽"+parseInt(parseInt(downbw_t)-downcount+parseInt(ret.downrate))+"KB");  				
			 
		    }
      },
	  beforeSubmit:function(postdata, formid)
		{
		   //var up = parseInt(jQuery("#upaccount").val());
		   var channelid = jQuery('#list_qos').jqGrid('getGridParam','selrow'); 
	       var ret = jQuery("#list_qos").jqGrid('getRowData',channelid);
		   var down = parseInt(jQuery("#downaccount").val());
		   var rowdata = jQuery("#list_qos").getRowData();
		   var upcount=0;
		   var downcount=0;
		   // $("#uprate").val();
		  // alert($("#uprate",formId).val);
		   for(var i=0; i<rowdata.length; i++)
			 {
			   upcount+=(parseInt(rowdata[i]['uprate']));
			   downcount+=(parseInt(rowdata[i]['downrate']));
			 }
			 upcount+=parseInt(postdata['uprate']);
			 downcount+=parseInt(postdata['downrate']);
		    if((upcount-parseInt(ret.uprate)) > upbw_t)	
           	   return [false,"超过了总的上行流量，请重新设置"]; 
		    else if((downcount-parseInt(ret.downrate)) > downbw_t)	
           	   return [false,"超过了总的下行流量，请重新设置"];
            else
			   {
                 return [true];	
               }			   
		}
	  	
	},
	{
		//closeAfterAdd: true, 
		//closeAfterEdit: true, 
		 afterShowForm:function ( ) {
         var rowdata = jQuery("#list_qos").getRowData();  
         var upcount = 0;
         var downcount = 0;		 
		  if(rowdata == "")
             {		   
    		    $("#uprate").val("可分配带宽"+parseInt(upbw_t)+"KB");
                $("#downrate").val("可分配带宽"+parseInt(downbw_t)+"KB");  				
			 }
          else
            {
			   for(var i=0; i<rowdata.length; i++)
			 {
			   upcount+=(parseInt(rowdata[i]['uprate']));
			   downcount+=(parseInt(rowdata[i]['downrate']));
			 }
			 
			 //alert(upcount);
			 //alert(downcount);
			 $("#uprate").val("可分配带宽"+parseInt(parseInt(upbw_t)-upcount)+"KB");
             $("#downrate").val("可分配带宽"+parseInt(parseInt(downbw_t)-downcount)+"KB");  				
			 
		    }		  
 
         
		 },
		beforeSubmit:function(postdata, formid)
		{
		   //var up = parseInt(jQuery("#upaccount").val());
		   var down = parseInt(jQuery("#downaccount").val());
		   var rowdata = jQuery("#list_qos").getRowData();
		   var upcount=0;
		   var downcount=0;
		   // $("#uprate").val();
		  // alert($("#uprate",formId).val);
		   for(var i=0; i<rowdata.length; i++)
			 {
			   upcount+=(parseInt(rowdata[i]['uprate']));
			   downcount+=(parseInt(rowdata[i]['downrate']));
			 }
			 upcount+=parseInt(postdata['uprate']);
			 downcount+=parseInt(postdata['downrate']);
		    if(upcount > upbw_t)	
           	   return [false,"超过了总的上行流量，请重新设置"]; 
		    else if(downcount > downbw_t)	
           	   return [false,"超过了总的下行流量，请重新设置"];
            else
			   {
                 return [true];	
               }			   
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
jQuery("#submit").click(function(){
 
 var qos; 
 var lend;
 var icmp;
 var ack;

 icmp = $("#mySelect").find("option:selected").val();  
 ack = $("#Select").find("option:selected").val();  

 
 
  
  if($("#lend").attr('checked') == false)
  {
   lend = 0;
  }
 else
  {
   lend = 1;
  }; 
   
$.post("advancetc_save.php",{lend:lend,icmp:icmp,ack:ack});

 });	



});
 
 //function afterShowADD(formId) {
//	$("#uprate").val('test'); 		
	
//} 
 
</script>
<style type="text/css">


#one{float:left;}
#three{float:left;}
#two{float:left;}

</style

</head>
<body>
  <h1>高级流量控制</h1>
 <table>
		<tr>
		  <td> 借用带宽：</td>
		  <td align ='left'><input  id="lend" type="checkbox"  <?php if($ceil == 1) echo "checked"  ?> /></td>
		</tr>
		<tr>
		  <td> ICMP协议(ping)：</td>
		  <td align ='left'> <select size="1" id="mySelect" name="myselect"></select></td>
		</tr>
        <tr>
		  <td>ACK包：</td>
		  <td align ='left'> <select size="1" id="Select" name="myselect"> </select></td>
		</tr>
		 
		 
</table> 

<h1>QOS明细</h1>
<div id="one"> 
    <table id="list_qos" class="scroll" cellpadding="0" cellspacing="0"></table>
    <div id="pager" class="scroll" style="text-align:center;"></div>
	 <br>
         <INPUT class = "inputButton_in" type="submit" name="提交" value="提交" id="submit" size="20" /> 
</div>
<div id="three">
&nbsp&nbsp&nbsp&nbsp
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