<?php
 
  require_once('_inc.php');
   
   $sql_fileout="select * from globalpara";
   $isfileout=$db->query2one($sql_fileout);
   
   $sql_im="select * from file_transter where function='即时通讯'";
   //echo $sql_im;
   $im=$db->query2($sql_im);

 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>简单流控列表</title>
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
jQuery(document).ready(function(){
jQuery("#filetype_list").jqGrid({
	url:'filecat_data.php?nd='+new Date().getTime(),
    //url:'data.php?nd='+new Date().getTime(),
	//editurl:'simple_tc_edit.php',
    datatype: "json",
	   colNames:['ID','文件类型','描述','放行'],
    colModel:[
	    {name:'id',index:'id', width:50}, 
  		{name:'key',index:'key', width:150},
   		{name:'address',index:'address', width:150},
   		{name:'pass',index:'pass', width:150,align:"center"}
    ],
    pager: jQuery('#filetype_pager'),
    rowNum:20,
    rowList:[20,40],
	//multiselect:true,
    sortname: 'id',
    viewrecords: true,
    sortorder: "asc",
    caption: "文件类型"
	});


jQuery("#filetype_list").jqGrid('navGrid','#filetype_pager',{edit:false,add:false,del:false,search:false});
});
</script>
<script type="text/javascript">

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
var tempfiletypeid;
jQuery(document).ready(function(){
jQuery("#ips_list").jqGrid({
    url:'file_data.php?nd='+new Date().getTime(),
	editurl:'file_edit.php',
    datatype: "json",
	   colNames:['ID','起始IP','结束IP','邮箱host','即时通讯host','网盘host','论坛host','FTP','TFTP'],
    colModel:[
  		{name:'id',index:'id', width:50,align:"left"},
		{name:'ips',index:'ips', width:150,align:"left",editable:true,editrules:{required:true}},
   		{name:'ipe',index:'ipe', width:150,align:"left",editable:true,editrules:{required:true}},
   		{name:'mail',index:'mail', width:200, align:"left",editable:true,editrules:{required:false}},
		{name:'im',index:'im', width:200,align:"left",editable:true,editrules:{required:false},edittype:"select",editoptions:{value:" 无:无;  <?php  foreach($im as $row ) { echo  $row[key].":".$row[key].";" ;  } ?>"}},
   		{name:'netdisk',index:'netdisk', width:200, align:"left",editable:true,editrules:{required:false}},
        {name:'bbs',index:'bbs', width:200, align:"left",editable:true,editrules:{required:false}},
        {name:'ftp',index:'ftp', width:100, align:"center",editable:true,editrules:{required:false}, editable: true,edittype:"checkbox",editoptions: {value:"1:0"}},
        {name:'tftp',index:'tftp', width:100, align:"center",editable:true,editrules:{required:false}, editable: true,edittype:"checkbox",editoptions: {value:"1:0"}}
       
    ],
	width:700,
    pager: jQuery('#pager'),
    rowNum:20,
    rowList:[20,40],
	//multiselect:true,
    sortname: 'key',
    viewrecords: true,
    sortorder: "asc",
	loadComplete:function(){
     
	   var temp = jQuery("#ips_list").getRowData();
       if(temp != "")	
	   {
		var firstid = temp[0]['id'];
        jQuery("#ips_list").jqGrid('setSelection',firstid);
		tempfiletypeid=firstid;
		
	   }
   	 
	},
	onSelectRow: function(id) {
	    //alert(jQuery("#select").val());
		//alert(id);
		if(id != null) 
		{    
		     var rowdata = jQuery("#filetype_list").getRowData();
			 var symbol="";  
			 var temp="";
			   for(var i=0; i<rowdata.length; i++)
			  {
			   temp="#"+rowdata[i]['id']+"filetype";
			   if($(temp).attr("checked")==true)
			    {
                   symbol=symbol+"1"+","				
				}
               else
                {
				  symbol=symbol+"0"+","
				}			   
			   
			 }
			if(symbol != "")
		    $.post("filetype_save.php",{filetype:symbol,filetypeid:tempfiletypeid});	  		
			
			var ret = jQuery("#ips_list").jqGrid('getRowData',id);
           //alert(ret.id);
		    jQuery("#filetype_list").jqGrid('setGridParam',{url:"filecat_data.php?ipids="+ret.id,page:1});
			jQuery("#filetype_list").jqGrid('setCaption',ret.ips+"至"+ret.ipe+"的文件类型显示").trigger('reloadGrid');	
			tempfiletypeid=id;	
		 
		}
	},
    caption: "放行规则表"
});
jQuery("#ips_list").jqGrid('navGrid','#pager',{edit:true,edittext:'编',add:true,addtext:'增', del:true, 
deltext:'删',search:false},
		{//edit 
                //afterShowForm:afterShowEdit,  
                afterSubmit:processAddEdit, 
                //beforeSubmit:validateData, 
				beforeSubmit:function(postdata, formid)
		          {   
		             if((checkip(postdata['ips'])==false) || (checkip(postdata['ipe'])==false) )
   	          	        return[false,'IP地址不正确'];
		             else                
		               return [true];	
           			   
		          },  
                afterShowForm:function ( ) {
	               var channelid = jQuery('#ips_list').jqGrid('getGridParam','selrow'); 
	               var ret = jQuery("#ips_list").jqGrid('getRowData',channelid);
				   var tempftp="#"+ret.id+"ftp";
				   var temptftp="#"+ret.id+"tftp";
				 
				   //alert(temptftp);
					  if($(tempftp).attr("checked")==true)
					    {
						  //alert(tempftp); 
				       	 $("#ftp").attr("checked",true);	
					    }
					  else
					   {
                       	 $("#ftp").attr("checked",false);
						 // alert(tempftp);
					   }	
                     if($(temptftp).attr("checked")==true)
				       	$("#tftp").attr("checked",true);	
					  else
                        $("#tftp").attr("checked",false); 					   
                                
			   	},				  
		       
               closeAfterAdd: true, 
               closeAfterEdit: true 
        },  
        {//add
                //afterShowForm:afterShowAdd,  
                beforeSubmit:function(postdata, formid)
		         {
		            if((checkip(postdata['ips'])==false) || (checkip(postdata['ipe'])==false) )
   	          	      return[false,'IP地址不正确'];
		            else
			          return [true];	
               			   
		          },
                afterSubmit:processAddEdit,
				//reloadAfterSubmit:true, 
				closeAfterAdd:true
				//beforeSubmit:validateData, 
               
               
        },
		{//DEL
		    afterSubmit:function(postdata, formid)
		{  
		   
		  jQuery("#filetype_list").jqGrid().trigger('reloadGrid');
		  
		   return [true];
		}
				
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
        } 
		
jQuery("#submit").click(function(){
  
  var rowdata = jQuery("#ips_list").getRowData();
  var tempftp="";
  var temptftp="";
  var symbolftp="";
  var symboltftp="";
  var symbolid="";
  var symbolisfileout="";
   for(var i=0; i<rowdata.length; i++)
			  {
			   tempftp="#"+rowdata[i]['id']+"ftp";
			   temptftp="#"+rowdata[i]['id']+"tftp";
			   symbolid=symbolid+rowdata[i]['id']+",";
			   if($(tempftp).attr("checked")==true)
			    {
                   symbolftp=symbolftp+"1"+","				
				}
               else
                {
				  symbolftp=symbolftp+"0"+","
				}
                
               if($(temptftp).attr("checked")==true)
			    {
                   symboltftp=symboltftp+"1"+","				
				}
               else
                {
				  symboltftp=symboltftp+"0"+","
				}	 			   
			   
			 }
	
  
 $.post("filetypepass_save.php",{ftp:symbolftp,tftp:symboltftp,id:symbolid});	

 var sr = jQuery("#ips_list").getGridParam('selrow');
 var rowData = jQuery("#ips_list").getRowData(sr);
 var tempfiletypeid = rowData['id'];

 var rowdata = jQuery("#filetype_list").getRowData();
			 var symbol="";  
			 var temp="";
			   for(var i=0; i<rowdata.length; i++)
			  {
			   temp="#"+rowdata[i]['id']+"filetype";
			   if($(temp).attr("checked")==true)
			    {
                   symbol=symbol+"1"+","				
				}
               else
                {
				  symbol=symbol+"0"+","
				}			   
			   
			 }
	 if($("#fileout").attr("checked")==true)
      symbolisfileout = "1";
    else
	  symbolisfileout = "0";	
 
  $.post("filetype_save.php",{filetype:symbol,filetypeid:tempfiletypeid,isfileout:symbolisfileout});	 
  			
   
 });	

	
});
</script>




</head>
<body>
 
<h1>防止文件外发</h1>
<table>
		<tr>
		 
		  <td align ='left'><input  id="fileout" type="checkbox" <?php if($isfileout['isfileout'] == 1) echo "checked"; ?>  /></td>
		   <td>启用</td>
		</tr>
		
</table> 
<h1>放行规则表</h1>

<table id="ips_list" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="pager" class="scroll" style="text-align:center;"></div>
<h1>文件类型</h1>

<table id="filetype_list" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="filetype_pager" class="scroll" style="text-align:center;"></div>

<br>
<INPUT class = "inputButton_in" type="submit" name="提交" value="提交" id="submit" size="20" />	
</body>
</html>
