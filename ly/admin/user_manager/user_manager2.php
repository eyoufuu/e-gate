<?php
  require_once('_inc.php');
   $sql_loginmode="select systemmode from globalpara;";
    $loginmode=$db->fetchone($sql_loginmode);
    $sql_policyname="select policyid,name from policy where stat=1;";
   $result=$db->fetchRows($sql_policyname);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
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

 var policynamearray = <?php
        
		$return_array = array();
        		
		$i=0;
	foreach($result as $row){
		 $return_array[$i][0]=$row['policyid'];
		 $return_array[$i][1]=$row['name'];
		  $i++;
	  }
	  // $return_array[0]="ok";  	   
	   $tarray = json_encode($return_array);
	    echo $tarray;
   ?>;
   var policynamestr="";
   var i=0; 
  for (i=0;i<policynamearray.length;i++)
  {
    if(i == policynamearray.length-1)
     {
	  policynamestr=policynamestr+policynamearray[i][0] +":"+ policynamearray[i][1];
	  break;
	 } 
    policynamestr=policynamestr+policynamearray[i][0] +":"+ policynamearray[i][1]+";"
  }
 
   
   //alert(policynamearray[0][]);
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
    url:'user_data2.php?nd='+new Date().getTime(),
	 editurl:'user_edit2.php',
    datatype: "json",
	   colNames:['id','用户名称','登陆账号','密码','IP地址','分配策略'],
    colModel:[
  		{name:'id',index:'id', width:30,align:"right",editable:true,hidden:true},
		{name:'name',index:'name', width:150,align:"left",editable:true,editrules:{required:true}},
      {name:'account',index:'account', width:150,align:"left",editable:true,editrules:{required:false},<?php if ($loginmode==0) echo "hidden:true "; else echo "hidden:false";  ?>},
   	{name:'passwd',index:'passwd', width:100, align:"left",editable:true,editrules:{required:false},<?php if ($loginmode==0) echo "hidden:true ";else echo "hidden:false"; ?>},
   	{name:'bindip',index:'bindip', width:100, align:"left",editable:true,editrules:{required:false},<?php if ($loginmode!=0) echo "hidden:true ";else echo "hidden:false"; ?>},
   	    {name:'policyid',index:'policyid', width:100, align:"left",editable:true,editrules:{required:true},edittype:"select",editoptions:{value:policynamestr}},								
    ],
	 width:650,
	 height:250,
    pager: jQuery('#pager'),
    rowNum:20,
    rowList:[20,40],
	 multiselect:true,
    sortname: 'id',
    viewrecords: true,
    sortorder: "asc",
    caption: "用户列表"
});
jQuery("#ips_list").jqGrid('navGrid','#pager',{edit:true,edittext:'编',add:true,addtext:'增', del:true, deltext:'删',search:false},
		{//edit 
           closeAfterAdd: true, 
           closeAfterEdit: true ,
           beforeSubmit:function(postdata, formid)
		    {
			   if(checkip(postdata['bindip'])==false )
			   return[false,'IP地址不正确'];
			   return [true,'ok'];
		    }
      },  
        {//add
            afterShowForm:afterShowAdd,  
            beforeSubmit:function(postdata, formid)
		        {
		            	if(checkip(postdata['bindip'])==false )
			          	return[false,'IP地址不正确'];
			            return [true,'ok'];
		        },
            closeAfterAdd: true, 
            closeAfterEdit: true 
        },
		{//DEL
			onclickSubmit:function(postdata, formid) 
			{
				var sr = jQuery("#ips_list").getGridParam('selrow');
            var rowData = jQuery("#ips_list").getRowData(sr);
            var retarr = {"bid" : rowData['id']};
            return retarr; 
			}
		}
		
		);
		 function afterShowAdd(formId) { 
				 //$("#tr_ids",formId).hide(); 		
			   	$("#tr_ids",formId).attr('disabled',true); 		
                // alert("edit");
                //do stuff after the form is rendered 
        } 
     
});


//地址范围
jQuery(document).ready(function(){
jQuery("#netseg_list").jqGrid({
    url:'netseg_data2.php?nd='+new Date().getTime(),
	 editurl:'netseg_edit2.php',
    datatype: "json",
	   colNames:['id','起始ip地址','结束ip地址','描述','监控','分配策略'],
    colModel:[
  		{name:'id',index:'id', width:30,align:"right",editable:true,hidden:true},
		{name:'ips',index:'ips', width:150,align:"left",editable:true,editrules:{required:true}},
   	{name:'ipe',index:'ipe', width:150,align:"left",editable:true,editrules:{required:true}},
   	{name:'name',index:'name', width:100, align:"left",editable:true,editrules:{required:true}},
   	{name:'monitor',index:'monitor', width:30, align:"left",editable:true,editrules:{required:true},edittype:"select",editoptions:{value:"1:是;0:否"}},
   	{name:'policyid',index:'policyid', width:100, align:"left",editable:true,editrules:{required:true},edittype:"select",editoptions:{value:policynamestr}},								
    ],
    width:650,
    height:90,
    pager: jQuery('#netseg_pager'),
    rowNum:10,
    rowList:[10,20],
	multiselect:true,
    sortname: 'id',
    viewrecords: true,
    sortorder: "asc",
    caption: "网段列表"
});
jQuery("#netseg_list").jqGrid('navGrid','#pager',{edit:true,edittext:'编',add:true,addtext:'增', del:true, deltext:'删',search:false},
		{//edit 
		      beforeSubmit:function(postdata, formid)
		       {
		          if((checkip(postdata['ips'])==false) || (checkip(postdata['ipe'])==false) )
			       return[false,'IP地址不正确'];
			       return [true,'ok'];
		       }, 
		      onclickSubmit:function(postdata, formid)
		       {
			      var sr = jQuery("#netseg_list").getGridParam('selrow');
	              var rowData = jQuery("#netseg_list").getRowData(sr);
	              var retarr = {"bid" : rowData['id']};
	              return retarr; 
		       },	 
		   
           closeAfterAdd: true, 
           closeAfterEdit: true
      },  
     {//add
           beforeSubmit:function(postdata, formid)
		        {
		          	if((checkip(postdata['ips'])==false) || (checkip(postdata['ipe'])==false) )
   	          	     return[false,'IP地址不正确'];
		            return [true,'ok'];
		        },
            afterShowForm:afterShowAdd,  
            closeAfterAdd: true, 
            closeAfterEdit: true 
     },
		{//DEL
			onclickSubmit:function(postdata, formid) 
			{
				var sr = jQuery("#netseg_list").getGridParam('selrow');
            var rowData = jQuery("#netseg_list").getRowData(sr);
            var retarr = {"bid" : rowData['id']};
            return retarr; 
			 }
		}
		
		);
		function afterShowAdd(formId) { 
				 //$("#tr_ids",formId).hide(); 		
			   	$("#tr_ids",formId).attr('disabled',true); 		
                // alert("edit");
                //do stuff after the form is rendered 
        }
});
</script>
</head>
<body>
<h1>网段列表</h1>
<table id="netseg_list" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="netseg_pager" class="scroll" style="text-align:center;"></div>
<h1>用户列表</h1>
<table id="ips_list" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="pager" class="scroll" style="text-align:center;"></div>
</body>
</html>
