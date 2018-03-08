<?php 
  require_once('_inc.php');   
   $sql_updown="select * from globalpara";
   $updown=$db->query2one($sql_updown);
   $isqos = $updown['isqosopen'];
   $sim_p2p = $updown['stc_p2p'];
   $upflow=$updown['upbw']/8;
   $downflow=$updown['downbw']/8;
   $percentupbw=$updown['percentupbw'];
   $percentdownbw=$updown['percentupbw'];   
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
var upbw_t=<?php
  $uptemp = json_encode(floor($upflow*$percentupbw/100));
	          echo $uptemp;               
  ?>;
  upbw_t=parseInt(upbw_t);
var downbw_t=<?php
  $downtemp = json_encode(floor($downflow*$percentdownbw/100));
	          echo $downtemp;               
  ?>;
   downbw_t=parseInt(downbw_t);
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
jQuery("#ips_list").jqGrid({
    url:'tc_simple_data.php?nd='+new Date().getTime(),
	editurl:'tc_simple_edit.php',
    datatype: "json",
	   colNames:['ID','起始IP','结束IP', '上行流量(KB)', '下行流量(KB)'],
    colModel:[
  		{name:'id',index:'id', width:30,align:"right"},
		{name:'ips',index:'ips', width:150,editable:true,editrules:{required:true}},
   		{name:'ipe',index:'ipe', width:150,editable:true,editrules:{required:true}},
   		{name:'upbw',index:'upbw', width:100, align:"right",editable:true,editrules:{required:true,number:true}},
   		{name:'downbw',index:'downbw', width:100, align:"right",editable:true,editrules:{required:true,number:true}}				
    ],
	width:650,
    pager: jQuery('#pager'),
    rowNum:20,
    rowList:[20,40],
	multiselect:true,
    sortname: 'id',
    viewrecords: true,
    sortorder: "asc",
    caption: "ip组列表"
});
jQuery("#ips_list").jqGrid('navGrid','#pager',{edit:true,edittext:'编',add:true,addtext:'增', del:true, 
deltext:'删',search:false},
		{//edit 
                afterShowForm:afterShowEdit,  
                
				afterSubmit:processAddEdit, 
                //beforeSubmit:validateData, 
				beforeSubmit:function(postdata, formid)
		{   
		  if((checkip(postdata['ips'])==false) || (checkip(postdata['ipe'])==false) )
   	          	  return[false,'IP地址不正确'];
		   var channelid = jQuery('#ips_list').jqGrid('getGridParam','selrow'); 
	       var ret = jQuery("#ips_list").jqGrid('getRowData',channelid);
		   var rowdata = jQuery("#ips_list").getRowData();
		   var upcount=0;
		   var downcount=0;
		  
		   for(var i=0; i<rowdata.length; i++)
			 {
			   upcount+=(parseInt(rowdata[i]['upbw']));
			   downcount+=(parseInt(rowdata[i]['downbw']));
			 }
			 upcount+=parseInt(postdata['upbw']);
			 downcount+=parseInt(postdata['downbw']);
		    if((upcount-parseInt(ret.upbw)) > upbw_t)	
           	   return [false,"超过了总的上行流量，请重新设置"]; 
		    else if((downcount-parseInt(ret.downbw)) > downbw_t)	
           	   return [false,"超过了总的下行流量，请重新设置"];
            else
			   {
                 return [true];	
               }			   
		},     
		       
                closeAfterAdd: true, 
               closeAfterEdit: true 
        },  
        {//add
                afterShowForm:afterShowAdd,  
                beforeSubmit:function(postdata, formid)
		     {
		      if((checkip(postdata['ips'])==false) || (checkip(postdata['ipe'])==false) )
   	          	  return[false,'IP地址不正确'];
			//alert("ok");
    	    var rowdata = jQuery("#ips_list").getRowData();
		    var upcount=0;
		    var downcount=0;
		
		   for(var i=0; i<rowdata.length; i++)
			 {
			   upcount+=(parseInt(rowdata[i]['upbw']));
			   downcount+=(parseInt(rowdata[i]['downbw']));
			 }
			 upcount+=parseInt(postdata['upbw']);
			 downcount+=parseInt(postdata['downbw']);
		    if(upcount > upbw_t)	
           	   return [false,"超过了总的上行流量，请重新设置"]; 
		    else if(downcount > downbw_t)	
           	   return [false,"超过了总的下行流量，请重新设置"];
            else
			   {
                 return [true];	
               }			   
		},
                afterSubmit:processAddEdit,
				//reloadAfterSubmit:true, 
				closeAfterAdd:true
				//beforeSubmit:validateData, 
               
               
        },
		{//DEL
		    /*
			onclickSubmit:function(postdata, formid) 
			{
				var sr = jQuery("#ips_list").getGridParam('selrow');
				s = jQuery("#ips_list").jqGrid('getGridParam','selarrrow');
				var str="-1";
				for(var i=0;i<s.length;i++)
				{
				   var rowData1 = jQuery("#ips_list").getRowData(s[i]);
				   str +=",";
				   str += rowData1.ids;
				}
				//var rowData = jQuery("#ips_list").getRowData(sr);
				var retarr = {"ids" : str};
				return retarr; 
			}
			*/
		}
		
		);
		function afterShowEdit(formId) {
				var channelid = jQuery('#ips_list').jqGrid('getGridParam','selrow'); 
	            var ret = jQuery("#ips_list").jqGrid('getRowData',channelid);
                var rowdata = jQuery("#ips_list").getRowData();  
                var upcount = 0;
                var downcount = 0;
			   
                				
		      if(rowdata == "")
                {
                  if(jQuery("#switch_p2p").attr('checked') == true)
                    {				  
    		          $("#upbw").val("可分配带宽"+parseInt(upbw_t*95/100)+"KB");
                      $("#downbw").val("可分配带宽"+parseInt(downbw_t*95/100)+"KB");  				
					}
                   else
                     {
					   $("#upbw").val("可分配带宽"+parseInt(upbw_t)+"KB");
                       $("#downbw").val("可分配带宽"+parseInt(downbw_t)+"KB");
					  }				   
			    }
             else
               {
			     for(var i=0; i<rowdata.length; i++)
			      {
			        upcount+=(parseInt(rowdata[i]['upbw']));
			        downcount+=(parseInt(rowdata[i]['downbw']));
			      }
			 
			     if(jQuery("#switch_p2p").attr('checked') == true)
				 {
			      $("#upbw").val("可分配带宽"+parseInt(parseInt(upbw_t*95/100)-upcount+parseInt(ret.upbw))+"KB");
                  $("#downbw").val("可分配带宽"+parseInt(parseInt(downbw_t*95/100)-downcount+parseInt(ret.downbw))+"KB");  				
			     }
				 else
				  {
			       $("#upbw").val("可分配带宽"+parseInt(parseInt(upbw_t)-upcount+parseInt(ret.upbw))+"KB");
                   $("#downbw").val("可分配带宽"+parseInt(parseInt(downbw_t)-downcount+parseInt(ret.downbw))+"KB");  				
			      }
		    }
        } 
        function afterShowAdd(formId) { 
		   var rowdata = jQuery("#ips_list").getRowData();  
           var upcount = 0;
           var downcount = 0;
             
		   
		  if(rowdata == "")
             {
			   if(jQuery("#switch_p2p").attr('checked') == true)
			    { 
    		     $("#upbw").val("可分配带宽"+parseInt(upbw_t*95/100)+"KB");
                 $("#downbw").val("可分配带宽"+parseInt(downbw_t*95/100)+"KB");  				
				}
				else
                 { 
    		      $("#upbw").val("可分配带宽"+parseInt(upbw_t)+"KB");
                  $("#downbw").val("可分配带宽"+parseInt(downbw_t)+"KB");  				
				 }				
			 }
          else
            {
			   for(var i=0; i<rowdata.length; i++)
			 {
			   upcount+=(parseInt(rowdata[i]['upbw']));
			   downcount+=(parseInt(rowdata[i]['downbw']));
			 }
			 
			 if(jQuery("#switch_p2p").attr('checked') == true)
			  {
			    $("#upbw").val("可分配带宽"+parseInt(parseInt(upbw_t*95/100)-upcount)+"KB");
                $("#downbw").val("可分配带宽"+parseInt(parseInt(downbw_t*95/100)-downcount)+"KB");  				
			  }
			  else
			   {
			     $("#upbw").val("可分配带宽"+parseInt(parseInt(upbw_t)-upcount)+"KB");
                 $("#downbw").val("可分配带宽"+parseInt(parseInt(downbw_t)-downcount)+"KB");  				
			   } 
		    }
				
        } 
		function validateData(formid)
		{
			alert("validate");
			return true;
		}
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
  
  var qos;
  var p2p;
 
  
  if($("#switch_p2p").attr('checked') == false)
  {
   p2p = 0;
  }
 else
  {
   p2p = $("#p2pselect").val();
  }; 
  
 
 //alert(qos);
 //alert(lend);
 //alert(icmp);
 //alert(ack);

$.post("simple_save.php",{p2p:p2p});			
  			
   
 });	

	
});
</script>
</head>
<body>
 
<h1>简单流量控制</h1>
<table>
   <tr>
     
	 <td align ='left' ><input type="checkbox" id="switch_p2p" value="switch_p2p" <?php if($sim_p2p != 0) echo "checked" ?> /></td>
     <td>限速p2p下载</td>   
	  <td align ='left'>  <select size="1" id="p2pselect"  style="visibility:hidden;">
                        <option value="5"  <?php if( $sim_p2p ==  5) echo  "selected= \"selected\" "?>>5%</option>
                        <option value="10" <?php if( $sim_p2p == 10) echo "selected=\"selected\" "?>>10%</option>
                        <option value="20" <?php if( $sim_p2p == 20) echo "selected=\"selected\" "?>>20%</option>
						<option value="30" <?php if( $sim_p2p == 30) echo "selected=\"selected\" "?>>30%</option>
						<option value="40" <?php if( $sim_p2p == 40) echo "selected=\"selected\" "?>>40%</option>
						<option value="50" <?php if( $sim_p2p == 50) echo "selected=\"selected\" "?>>50%</option>
						<option value="60" <?php if( $sim_p2p == 60) echo "selected=\"selected\" "?>>60%</option>
						
                </select></td>   
  <tr/>
</table> 
<INPUT class = "inputButton_in" style="margin-left:500px;" type="submit" name="执行" value="执行" id="submit" size="20" />	
<h1>规则表</h1>
<table id="ips_list" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="pager" class="scroll" style="text-align:center;"></div>
</body>
</html>
