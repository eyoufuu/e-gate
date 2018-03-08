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
   error_reporting(E_ALL);
?>

<?php
function getcolor()
{
	static $colorvalue;
	if($colorvalue=="class='bgFleet'")
		$colorvalue="";
	else
		$colorvalue="class='bgFleet'";
	return($colorvalue);
}

$sql = "select policyid, name, description from policy where stat=1 order by create_sort";
$arr = $db->fetchRows($sql);
$total_policy = count($arr);
?>

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
<script src="../js/dialog.js"></script>
<script src="../js/jquery-ui-1.7.1.custom.min.js" type="text/javascript"></script>
<script src="../js/jquery.layout.js" type="text/javascript"></script>
<script src="../js/i18n/grid.locale-cn.js" type="text/javascript"></script>

<script src="../js/jquery.jqGrid.min.js" type="text/javascript"></script>
<script src="../js/jquery.tablednd.js" type="text/javascript"></script>
<script src="../js/jquery.contextmenu.js" type="text/javascript"></script>
<script src="../js/ui.multiselect.js" type="text/javascript"></script>

<script type="text/javascript">

jQuery(document).ready(function(){
	jQuery("#policy_list").jqGrid({
	    url:'policy_data.php?nd='+new Date().getTime(),
		editurl:'policy_buttonevent.php',
	    datatype: "json",
		colNames:['id','create_id','策略名称','策略描述'],
	    colModel:[
	  		{name:'id',index:'id', width:100,align:"right",hidden:true},
	  		{name:'create_sort',index:'create_sort', width:100,align:"right",hidden:true},
	   		{name:'policy_name',index:'policy_name', width:50,editable:true,editrules:{required:true}},
	   		{name:'policy_description',index:'policy_description', width:150,editable:true,editrules:{required:true}}			
	    ],
	    pager: jQuery('#pager'),
	    rowNum:20,
	    rowList:[20,40],
	    height:500,
	    width:1000,
		multiselect:false,
	    sortname: 'create_sort',
	    viewrecords: true,
	    sortorder: "asc",
	    caption: "策略列表"
	});
	jQuery("#policy_list").jqGrid('navGrid','#pager',{edit:false,add:false,del:false,search: false})
	.navButtonAdd('#pager',{   
   		caption:"del",    
   		buttonicon:"ui-icon-add",    
   		onClickButton: function(){    
			var id = jQuery("#policy_list").jqGrid('getGridParam','selrow');
			if (id)	{
				var ret = jQuery("#policy_list").jqGrid('getRowData',id);
				if(ret.id==0)
				{
					alert("默认策略不能删除");
				}
				else
				{
					var answer = confirm("确实要删除选中的策略吗?");
					if(answer)
					{
						window.location.href="del_policy.php?id="+ret.id;
					}	
				}	
			} else { alert("请选择一条记录");}
		},    
   		position:"first"  
	}) 
	.navButtonAdd('#pager',{   
   		caption:"edit",    
   		buttonicon:"ui-icon-add",    
   		onClickButton: function(){    
			var id = jQuery("#policy_list").jqGrid('getGridParam','selrow');
			if (id)	{
				var ret = jQuery("#policy_list").jqGrid('getRowData',id);
				window.location.href="policy_set.php?id="+ret.id;
			} else { alert("请选择一条记录");}
		},    
   		position:"first"  
	})
	.navButtonAdd('#pager',{   
   		caption:"add",    
   		buttonicon:"ui-icon-add",    
   		onClickButton: function(){    
			var total_count=<?php echo $total_policy;?>;
			if(parseInt(total_count)>=100)
			{
				alert("您添加的策略已达最大值，不允许添加新的策略！");
			}
			else
				window.location.href="add_policy.php";
		},    
   		position:"first"  
	});
	});

</script>
</head>

<body>
<div class="bodyTitle">
	<div class="bodyTitleLeft"></div>
  <div class="bodyTitleText">策略管理</div>
</div>
<br>

<table id="policy_list" class="scroll" cellpadding="0" cellspacing="0"></table>

<!-- pager definition. class scroll tels that we want to use the same theme as grid -->
<div id="pager" class="scroll" style="text-align:center;"></div>
</body>

</html>

