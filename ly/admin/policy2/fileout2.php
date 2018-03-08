<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php

  require_once('_inc.php');
  $SQL="select function from file_transter group by function";
  $result = $db->fetchRows( $SQL );
  $SQL2="select * from file_transter ";
  $result2 = $db->fetchRows( $SQL2 );
  //echo "ok";
  
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
   	 url:'fileout_data.php?nd='+new Date().getTime(),
	 editurl:'fileout_edit.php',
    datatype: "json",
   	colNames:['类型','名字', '地址'],
   	colModel:[
   		{name:'function',index:'function', width:55,editable:true,editrules:{required:true}},
   		{name:'key',index:'key', width:90,editable:true,editrules:{required:true}},
   		{name:'address',index:'adress', width:100,editable:true,editrules:{required:true}}
		//{name:'block',index:'block', width:40, align:"center",editable:true,editrules:{required:true}, editable: true,edittype:"checkbox",editoptions: {value:"0:1"}}
   		
   	],
	width:650,
	height:250,
   	rowNum:10,
   	rowList:[10,20,30],
   	pager: jQuery('#pgtoolbar1'),
    sortname: 'address',
	multiselect:false,
    viewrecords: true,
	rownumbers: true,
	//sortorder: "desc",
    caption:"防止文件外发",
    //editurl:"someurl.php",
	toolbar: [true,"top"]
   });
   /*
   var initarray = <?php
        
		$return_array = array();
        		
		$i=0;
	foreach($result2 as $row){
		 $return_array[$i][0]=$row['address'];
		 $return_array[$i][1]=$row['block'];
		  $i++;
	  }
	  // $return_array[0]="ok";  	   
	   $initarray = json_encode($return_array);
	    echo $initarray;
   ?>;
   */
   
   //jQuery("#toolbar1").jqGrid('navGrid','#pgtoolbar1',{edit:true,add:true,del:true});
    jQuery("#toolbar1").jqGrid('navGrid','#pgtoolbar1',{edit:true,edittext:'编',add:true,addtext:'增', del:true, deltext:'删',search:false},
		{//edit 
           closeAfterAdd: true, 
           closeAfterEdit: true ,
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
			onclickSubmit:function(postdata, formid) 
			{
		     var sr = jQuery("#toolbar1").getGridParam('selrow');
             var rowData = jQuery("#toolbar1").getRowData(sr);
             var retarr = {"key" : rowData['address']};
             return retarr; 
			}
		}
		
		);
		 function afterShowAdd(formId) { 
				 //$("#tr_ids",formId).hide(); 		
			   	$("#tr_ids",formId).attr('disabled',true); 		
                // alert("edit");
                //do stuff after the form is rendered 
        };
     
    

     $("#t_toolbar1").append("<select size='1' id='select' name='select'><option>全部</option><?php foreach($result as $row ) { echo "<option>$row[function]</option>" ; } ?></select>");
     
	 $("select","#t_toolbar1").change(function(){
	    /*
          var rows= jQuery("#toolbar1").jqGrid('getRowData');
          //var p="";
           for(var i=0;i<rows.length;i++){
            var temp="#"+rows[i]['key'];
	        //var paras=new Array();
             for(var j=0;j<initarray.length;j++)
               {
			    if(rows[i]['address'] == initarray[j][0])
                  if($(temp).attr("checked")==true)
			         initarray[j][1]=0;        
			       else
			          initarray[j][1]=1;
                continue;					  
			   }			 
		     
           } ;
		 */  
		   
		   var fun=$(this).val();
		   jQuery("#toolbar1").jqGrid('setGridParam',{url:"fileout_data.php?q=1&keys="+encodeURI(fun),page:1});
	       jQuery("#toolbar1").jqGrid('setCaption',$(this).val()).trigger('reloadGrid');	
		   // jQuery("#toolbar1").jqGrid('navGrid','hideCol',"key");
       
	    });  
   
    
     /*
     $("#t_toolbar1").append("&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<input type='checkbox' id='all' value='全选' style='height:13px;font-size:-3' />");
       $("#all","#t_toolbar1").click(function(){
	        var rows= jQuery("#toolbar1").jqGrid('getRowData');
		   if($(this).attr("checked")==true)
		    {
		      for(var i=0;i<rows.length;i++){
              var temp="#"+rows[i]['key'];
	          $(temp).attr("checked",true)           
		    }
		   }
		   else
		   {
		    for(var i=0;i<rows.length;i++){
              var temp="#"+rows[i]['key'];
	          $(temp).attr("checked",false)
		   
		     } 
		   }
       });
	  */ 
       $("#submit").click(function(){
	      
		   /*
		   var rows= jQuery("#toolbar1").jqGrid('getRowData');
       
           for(var i=0;i<rows.length;i++){
            var temp="#"+rows[i]['key'];
	        //var paras=new Array();
             for(var j=0;j<initarray.length;j++)
               {
			    if(rows[i]['address'] == initarray[j][0])
                  if($(temp).attr("checked")==true)
			         initarray[j][1]=0;        
			       else
			          initarray[j][1]=1;
                continue;					  
			   }			 
		     
           } ;
		   var p="";
		   for(var k=0;k<initarray.length;k++){
              p=p+initarray[k][0]+",";
			  p=p+initarray[k][1]+",";
			  
		    };
		    $.post("change.php",{vv:p});
		   
            alert("提交成功");	 
          */			
      });

    
		  
	
   
});
</script>
</head>
<body>
  <h1>防止文件外发</h1>

<table id="toolbar1" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="pgtoolbar1" class="scroll" style="text-align:center;"></div>
</br>
<INPUT class = "inputButton_in" type="submit" name="提交" value="提交" id="submit" size="20" >	
 
</body>
</html>