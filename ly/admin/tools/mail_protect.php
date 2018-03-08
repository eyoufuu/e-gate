<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php

  require_once('_inc.php');
  
 // $SQL2="select * from file_transter ";
 // $result2 = $db->fetchRows( $SQL2 );
  //echo "ok";
  $sql_fileout="select * from globalpara";
  $isfile=$db->query2one($sql_fileout); 
  
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

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
jQuery("#toolbar1").jqGrid({
   	 url:'mail_data.php?nd='+new Date().getTime(),
	 editurl:'mail_edit.php',
    datatype: "json",
   	colNames:['ID','邮箱名称','地址','阻挡'],
   	colModel:[
   		{name:'id',index:'id', width:20},
   		{name:'key',index:'key', width:90,editable:true,editrules:{required:true}},
   		{name:'address',index:'adress', width:100,editable:true,editrules:{required:true}},
	    {name:'block',index:'block', width:40, align:"center",editable:true,editrules:{required:true}, editable: true,edittype:"checkbox",editoptions: {value:"0:1"}}
   		
   	],
	width:650,
	height:200,
   	rowNum:10,
   	rowList:[10,20,30],
   	pager: jQuery('#pgtoolbar1'),
    sortname: 'id',
	multiselect:false,
    viewrecords: true,
	rownumbers: true,
	//sortorder: "desc",
    caption:"防止文件外发"
    //editurl:"someurl.php",
	//toolbar: [true,"top"]
   });
  
  jQuery("#toolbar1").jqGrid('navGrid','#pgtoolbar1',{edit:true,edittext:'编',add:true,addtext:'增', del:true, deltext:'删',search:false},
		{//edit 
           closeAfterAdd: true, 
           closeAfterEdit: true ,
		    afterShowForm:function ( ) {
	               var channelid = jQuery('#toolbar1').jqGrid('getGridParam','selrow'); 
	               var ret = jQuery("#toolbar1").jqGrid('getRowData',channelid);
				   var tempfiletype="#"+ret.id+"mail";
				
				 
				   //alert(temptftp);
					  if($(tempfiletype).attr("checked")==true)
					    {
						  //alert(tempftp); 
				       	 $("#block").attr("checked",true);	
					    }
					  else
					   {
                       	 $("#block").attr("checked",false);
						 // alert(tempftp);
					   }	
                  			   
                                
			   	}				  
        },  
        {//add
            //afterShowForm:afterShowAdd,  
           // beforeSubmit:function(postdata, formid)
		    //   {
		     //   	if(postdata['function']=="" )
			  //   	return[false,'类型不能为空'];
			        //return [true,'ok'];
		      // },
            closeAfterAdd: true, 
            closeAfterEdit: true 
        },
		{//DEL
			
		}
		
		);
		
     
});
</script>

<script type="text/javascript">
function checkmailtype(types)
{   
	var re = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/;  //正则表达式   
	if(re.test(types))
	 {
	   //if(RegExp.$1<256 && RegExp.$2<256 && RegExp.$3<256 && RegExp.$4<256)
	    return true;
	 }	
	return false;
}

jQuery(document).ready(function(){
jQuery("#toolbar2").jqGrid({
   	 url:'mails_data.php?nd='+new Date().getTime(),
	 editurl:'mails_edit.php',
    datatype: "json",
   	colNames:['ID','发件人','收件人','主题','正文','阻挡'],
   	colModel:[
   		{name:'id',index:'id', width:20},
   		{name:'send',index:'send', width:90,editable:true,editrules:{required:false}},
   		{name:'accept',index:'accept', width:100,editable:true,editrules:{required:false}},
		{name:'title',index:'title', width:100,editable:true,editrules:{required:false}},
		{name:'content',index:'content', width:100,editable:true,editrules:{required:false}},
	    {name:'block',index:'block', width:40, align:"center",editable:true,editrules:{required:true}, editable: true,edittype:"checkbox",editoptions: {value:"0:1"}}
   		
   	],
	width:650,
	height:150,
   	rowNum:10,
   	rowList:[10,20,30],
   	pager: jQuery('#pgtoolbar2'),
    sortname: 'id',
	multiselect:false,
    viewrecords: true,
	rownumbers: true,
	//sortorder: "desc",
    caption:"防止文件外发"
    //editurl:"someurl.php",
	//toolbar: [true,"top"]
   });
  
  jQuery("#toolbar2").jqGrid('navGrid','#pgtoolbar2',{edit:true,edittext:'编',add:true,addtext:'增', del:true, deltext:'删',search:false},
		{//edit 
           closeAfterAdd: true, 
           closeAfterEdit: true,
		    beforeSubmit:function(postdata, formid)
			 {
			  if((checkmailtype(postdata['send'])==false) ||(checkmailtype(postdata['accept'])==false) )
   	          	  return[false,'邮箱格式不正确'];
			  else
                  return[true]; 			  
			 },
		    afterShowForm:function ( ) {
	               var channelid = jQuery('#toolbar2').jqGrid('getGridParam','selrow'); 
	               var ret = jQuery("#toolbar2").jqGrid('getRowData',channelid);
				   var tempfiletype="#"+ret.id+"mails";
                    if($(tempfiletype).attr("checked")==true)
					    {  
					      $("#block").attr("checked",true);	
					    }
					  else
					    {
                       	  $("#block").attr("checked",false);
					    }	
                 }				  
        },  
        {//add
             beforeSubmit:function(postdata, formid)
			 {
			  if((checkmailtype(postdata['send'])==false) || (checkmailtype(postdata['accept'])==false)) 
			     return[false,'特征码格式不正确'];
			  else
                 return[true]; 			  
			 },
            closeAfterAdd: true, 
            closeAfterEdit: true 
        },
		{//DEL
			
		}
		
		);
		
      $("#submit").click(function(){
	      
		  var rowdata = jQuery("#toolbar1").getRowData();
	      var symbol="";  
		  var sym="1";
	      var temp="";
		  var ids="";
			   for(var i=0; i<rowdata.length; i++)
			  {
			    ids=ids+rowdata[i]['id']+",";
			    temp="#"+rowdata[i]['id']+"mail";
			   if($(temp).attr("checked")==true)
			     {
                   symbol=symbol+"0"+","				
				 }
               else
                 {
				   symbol=symbol+"1"+","
				 }			   
			   
			 }
	      if($("#fileout").attr("checked")==true)
              symbolisfileout = "1";
          else
	         symbolisfileout = "0";	
		
        $.post("filetype_save.php",{filetype:symbol,filetypeid:ids,isfileout:symbolisfileout,sym:sym});	 		
      
	     
		 var rowdatas = jQuery("#toolbar2").getRowData();
	      var symbols="";  
		  var syms="1";
	      var temps="";
		  var idss="";
			   for(var i=0; i<rowdatas.length; i++)
			  {
			    idss=idss+rowdatas[i]['id']+",";
			    temps="#"+rowdatas[i]['id']+"mails";
			   if($(temps).attr("checked")==true)
			     {
                   symbols=symbols+"0"+","				
				 }
               else
                 {
				   symbols=symbols+"1"+","
				 }			   
			   
			 }
	      	
		
        $.post("mails_save.php",{mails:symbols,mailid:idss});	 		 
	  
	  
	  });

    
		  
	
   
});
</script>
</head>
<body>

<h1>邮件外发检测</h1>
<table>
   <tr>
	  <td align ='left'><input  id="fileout" type="checkbox" <?php if($isfile['ismailopen'] == 1) echo "checked"; ?>  /></td>
	  <td>启用</td>
   </tr>
		
</table> 
<h1>邮箱设置</h1>
<table id="toolbar1" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="pgtoolbar1" class="scroll" style="text-align:center;"></div>
<h1>邮件内容设置</h1>
<table id="toolbar2" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="pgtoolbar2" class="scroll" style="text-align:center;"></div>

</br>
<INPUT class = "inputButton_in" type="submit" name="提交" value="提交" id="submit" size="20" >	
 
</body>
</html>