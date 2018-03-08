<?php
   require_once('_inc.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>策略管理</title>
<link href="../common/main.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../common/common.js"></script>

<link rel="stylesheet" type="text/css" media="screen" href="../themes/redmond/jquery-ui-1.7.1.custom.css" />
<link rel="stylesheet" type="text/css" media="screen" href="../themes/ui.jqgrid.css" />
<link rel="stylesheet" type="text/css" media="screen" href="../themes/ui.multiselect.css" />

<script src="../js/jquery.js" type="text/javascript"></script>
<script src="../js/jquery-ui-1.8.custom.min.js" type="text/javascript"></script>
<script src="../js/jquery.layout.js" type="text/javascript"></script>
<script src="../js/i18n/grid.locale-cn.js" type="text/javascript"></script>
<script src="../js/jquery.jqGrid.min.js" type="text/javascript"></script>
<script src="../js/jquery.tablednd.js" type="text/javascript"></script>
<script src="../js/jquery.contextmenu.js" type="text/javascript"></script>
<script src="../js/ui.multiselect.js" type="text/javascript"></script>

<script type="text/javascript">
var format = function (number, form)
{
    var forms = form.split('.'), number = '' + number, numbers = number.split('.')
        , leftnumber = numbers[0].split('')
        , exec = function (lastMatch) {
            if (lastMatch == '0' || lastMatch == '#') {
                if (leftnumber.length) {
                    return leftnumber.pop();
                } else if (lastMatch == '0') {
                    return lastMatch;
                } else {
                    return '';
                }
            } else {
                return lastMatch;
            }
    }, string
    
    string = forms[0].split('').reverse().join('').replace(/./g, exec).split('').reverse().join('');
    string = leftnumber.join('') + string;
    
    if (forms[1] && forms[1].length)
	{
        leftnumber = (numbers[1] && numbers[1].length) ? numbers[1].split('').reverse() : [];
        string += '.' + forms[1].replace(/./g, exec);
    }
    return string.replace(/\.$/, '');
};
jQuery(document).ready(function(){
	jQuery("#policy_list").jqGrid({
	    url:'policy_data.php?nd='+new Date().getTime(),
		editurl:'policy_edit.php',
	    datatype: "json",
		colNames:['id','create_id','策略名称','策略描述','策略详情'],
	    colModel:[
	  		{name:'id',index:'id', width:50,align:"right",editable:false,sortable:true},
	  		{name:'create_sort',index:'create_sort',align:"center",width:1,align:"right",hidden:true},
	   		{name:'policy_name',index:'policy_name',align:"left", width:200,editable:true,editrules:{required:true}},
	   		{name:'policy_description',index:'policy_description', width:400,editable:true,sortable:false,edittype:"textarea", editoptions:{rows:"3",cols:"20"}},
	   		{name:'policy_info',index:'policy_info', width:100,editable:false,sortable:false}			
	    ],
	    pager: jQuery('#pager'),
	    rowNum:20,
	    rowList:[20,40],   
		multiselect:false,
	    sortname: 'id',
	    viewrecords: true,
	    sortorder: "asc",
	    caption: "策略列表",
	    loadComplete:function(){
		jQuery("#policy_list").jqGrid('setSelection',"0");
		}		
	});
		jQuery("#policy_list").jqGrid('navGrid','#pager',{edit:true,edittext:'编',add:true,addtext:'增',del:true,deltext:'删',search: false},
		{
			  closeAfterAdd: true, 
			  closeAfterEdit: true 
		},
		{
			 beforeSubmit:function(postdata, formid)
	        {
				var sr = jQuery("#policy_list").getGridParam('records');
				if(sr>2)
				{
		          	return[false,'超过策略最大条数'];
				}
		       return [true,'ok'];
	        },
			 closeAfterAdd: true, 
			 closeAfterEdit: true 
		},
		{			
			beforeSubmit:function(postdata, formid)
			{
				var sr = jQuery("#policy_list").getGridParam('selrow');
		        var rowData = jQuery("#policy_list").getRowData(sr);
		   		if(rowData['id'] ==0)
		   		{
		   			return[false,"该策略不能删除！"]; 
		   		}	        
		        return [true,""]; 
			}
		});		
});
//jQuery(document).ready(function() {init_web(0);});
</script>
</head>
<body>
<h1>所有策略列表</h1>
<table id="policy_list" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="pager" class="scroll" style="text-align:center;"></div>
</body>
</html>

