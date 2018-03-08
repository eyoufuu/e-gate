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
<script language="javascript" type="text/javascript" src="../js/table_set.js"></script>
<script type="text/javascript">
//	var g_policy_data;
    var g_json_select;
	function onShowHide(tt)
	{
		$("#"+tt).toggle();
	}

    function on_select(tt)
	{
	    var x = "#check_all_" + tt;//总开关
		var value = $(x).attr("checked");
		for(var i = 0;i<g_json_select[tt].length;i++)
		{
			  var input = "#pro_id_" +g_json_select[tt][i].proid ;
			  $(input).attr("checked",value);
		}
		var div = "#div_id_" + tt;
		$(div).show();
	}

jQuery(document).ready(function(){
	function onDataReceived(json)
	{
	   g_json_select = json;
	   for(var item in json)
	   {
    	  var x = "#div_id_"+item;
		  $(x).append("&nbsp;&nbsp;&nbsp;")
		  for(var i =0;i<json[item].length;i++)
		  {
		      //alert(json[item][i].name);
			  var input = "<input id ='pro_id_" +json[item][i].proid +"' type='checkbox' />"+json[item][i].name+"&nbsp&nbsp";
			  $(x).append("&nbsp;")
			  $(x).append(input);
			  
		  }
		  $(x).append("<h3></h3>")
		  $(x).hide();
	   }
	}
	
	$.ajax({
        url: "pro_catagory_data.php",
		cache:false,
        method: 'GET',
        dataType: 'json',
        success: onDataReceived
	});
});
</script>

<script type="text/javascript">
function setkeyword(mydata)
{   
  for(var i=0;i<mydata.length;i++) 
  { 
     jQuery("#keyword_list").jqGrid('addRowData',i,mydata[i]);        
  }
}	
jQuery(document).ready(function(){
	jQuery("#policy_list").jqGrid({
	    url:'policy_data.php?nd='+new Date().getTime(),
		editurl:'policy_edit.php',
	    datatype: "json",
		colNames:['id','create_id','策略名称','策略描述'],
	    colModel:[
	  		{name:'id',index:'id', width:1,align:"right",hidden:true},
	  		{name:'create_sort',index:'create_sort',align:"center",width:1,align:"right",hidden:true},
	   		{name:'policy_name',index:'policy_name',align:"left", width:200,editable:true,editrules:{required:true}},
	   		{name:'policy_description',index:'policy_description', width:400,editable:true,sortable:false,edittype:"textarea", editoptions:{rows:"3",cols:"20"}}			
	    ],
	    pager: jQuery('#pager'),
	    rowNum:20,
	    rowList:[20,40],   
		multiselect:false,
	    sortname: 'create_sort',
	    viewrecords: true,
	    sortorder: "asc",
	    caption: "策略列表",
	    loadComplete:function(){
		jQuery("#policy_list").jqGrid('setSelection',"0");
		},
	    onSelectRow: function(ids)
			{
				if(ids == null)
				{
					ids=0;					
				}
				else
				{
					var rowData = jQuery("#policy_list").getRowData(ids);
					var pid = rowData["id"];
					
					/*var tab = $('#tabs').tabs();
					var select = tab.tabs('option', 'selected'); 
					$.ajax({
				            url: "policy_data2.php?pid="+pid+"&tab="+select,
							cache:false,
				            method: 'GET',
				            dataType: 'json',
				            success: onDataReceived
				    	});*/
					$.ajax({
			            url: "policy_data2.php?pid="+pid,
						cache:false,
			            method: 'GET',
			            dataType: 'json',
			            success: pol_onDataReceived
			    	});
				//	jQuery("#web_list").clearGridData();
				//	jQuery("#web_list").setGridParam({url:'web_catagory_data.php?p='+pid}).trigger("reloadGrid");
				//	var sr = jQuery("#web_list").getGridParam('selrow');			
				//	
				//	document.getElementById("wcpass3").checked=true;
				//	jQuery("#web_list").setRowData("2",{log:'<input type="checkbox" checked="checked" />'});   
				//	jQuery("#web_list").setRowData("3",{log:$("#wclog3").attr("checked","checked");});       
				}
			}
		
	});
		jQuery("#policy_list").jqGrid('navGrid','#pager',{edit:true,add:true,del:true,search: false},
		{
			  closeAfterAdd: true, 
			  closeAfterEdit: true 
		},
		{
			 closeAfterAdd: true, 
			 closeAfterEdit: true 
		},
		{
			/*beforeShowForm:function beforeshowdel(formId)
			{
				var sr = jQuery("#policy_list").getGridParam('selrow');
				var rowData = jQuery("#policy_list").getRowData(sr);
				if(rowData['id'] ==0)
				{
					alert("该策略不能删除！");
				}				
			}*/
			beforeSubmit:function(postdata, formid) {
			var sr = jQuery("#policy_list").getGridParam('selrow');
	        var rowData = jQuery("#policy_list").getRowData(sr);
	   		if(rowData['id'] ==0)
	   		{
	   			return[false,"该策略不能删除！"]; 
	   		}	        
	         return [true,""]; 
			}
		}
		);
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
	
		function filldata_policy(data)
		{
			$("#time_open").attr("checked",data['time']==1?"checked":"");			
			var tmp = parseInt(data['times1']);					
			$("#time_start1").attr("value",format(tmp/100,'00')+":"+format(tmp%100,'00'));
			tmp = parseInt(data['timee1']);	 
			$("#time_end1").attr("value",format(tmp/100,'00')+":"+format(tmp%100,'00'));
			tmp = parseInt(data['times2']);	 
			$("#time_start2").attr("value",format(tmp/100,'00')+":"+format(tmp%100,'00'));
			tmp = parseInt(data['timee2']);
			$("#time_end2").attr("value",format(tmp/100,'00')+":"+format(tmp%100,'00'));
			time_control();
		//	$("time_start1").attr("value",’00:30’);//;
			$("#d1").attr("checked",parseInt(data['week'])&64?"checked":"");
			$("#d2").attr("checked",parseInt(data['week'])&32?"checked":"");
			$("#d3").attr("checked",parseInt(data['week'])&16?"checked":"");
			$("#d4").attr("checked",parseInt(data['week'])&8?"checked":"");
			$("#d5").attr("checked",parseInt(data['week'])&4?"checked":"");
			$("#d6").attr("checked",parseInt(data['week'])&2?"checked":"");
			$("#d7").attr("checked",parseInt(data['week'])&1?"checked":"");

			
			$("#audit_smtp").attr("checked",data['smtpaudit']==1?"checked":"");
			$("#audit_pop3").attr("checked",data['pop3audit']==1?"checked":"");
			$("#audit_post").attr("checked",data['postaudit']==1?"checked":"");	

			$("#webfilter").attr("checked",data['webfilter']==1?"checked":"");

			$("#filetypefilter").attr("checked",data['filetypefilter']==1?"checked":"");
			$("#keywordfilter").attr("checked",data['keywordfilter']==1?"checked":"");

			   
			var i=0;//置空
			for(i=0;i<256;i++)
			{
				var pro = "#pro_id_"+i;
				$(pro).attr("checked","");
				
				var pass = "#wcpass"+i;
				var log = "#wclog"+i;
				$(pass).attr("checked","");
				$(log).attr("checked","");
			}
			jQuery("#keyword_list").clearGridData();
			for(i=0;i<50;i++)
			{
				var pass = "#fcpass"+i;
				var log = "#fclog"+i;
				$(pass).attr("checked","");
				$(log).attr("checked","");
			}
			//赋值

			var pro = data['proctl'].split("|");
			for(i=0;i<pro.length;i++)
			{
			    if(pro[i])
				{
					var id = "#pro_id_"+pro[i];
					$(id).attr("checked","checked");
				}
			}

			var wc = data['webinfo'].split("|");
	
			for(i=0;i<wc.length;i++)
			{
				var dt=wc[i].split(",");
				var idpass= "#wcpass"+dt[0];
				var idlog = "#wclog"+dt[0];
				switch(dt[1])
				{
				case '1':
					$(idlog).attr("checked","checked");
		        	break;
				case '2':
					$(idpass).attr("checked","checked");
					break;
				case '3':
					$(idlog).attr("checked","checked");
					$(idpass).attr("checked","checked");
					break;
				default:
					break;
				}
			}
			var fc = data['fileinfo'].split("|");
			for(i=0;i<fc.length;i++)
			{				
				var dt = fc[i].split(",");
				var pass = "#fcpass"+dt[0];
				var log = "#fclog"+dt[0];
				switch(dt[1])
				{
				case '1':
					$(log).attr("checked","checked");
		        	break;
				case '2':
					$(pass).attr("checked","checked");
					break;
				case '3':
					$(log).attr("checked","checked");
					$(pass).attr("checked","checked");
					break;
				default:
					break;
				}
			}	
			var wk=data['keywordutf'].split("|");
		   var mydata = new Array();	
		   for(i=0;i<wk.length;i++)
		   	{
			   	if(wk[i]=="")
				   	continue;
			     var keywordg = new Array();
				  var dt = wk[i].split(",");
				  keywordg["keyword"]=dt[0];				
			  switch(dt[1])
				{
				  	case '0':
					  	keywordg['block'] = "<input id='kwb_"+i+"' type='checkbox'/>";
						keywordg['log'] = "<input id='kwl_"+i+"' type='checkbox' />";
						break;
					case '1':
						keywordg['block'] = "<input id='kwb_"+i+"' type='checkbox'/>";
						keywordg['log'] = "<input id='kwl_"+i+"' type='checkbox' checked='checked' />";
						break;
					case '2':
						keywordg['block'] = "<input id='kwb_"+i+"' type='checkbox' checked='checked' />";
						keywordg['log'] = "<input id='kwl_"+i+"' type='checkbox'/>";
						break;
					case '3':
						keywordg['block'] = "<input id='kwb_"+i+"' type='checkbox' checked='checked' />";
						keywordg['log'] = "<input id='kwl_"+i+"' type='checkbox' checked='checked' />";
						break;
					default:
						break;
				}
				//alert(keywordg['block']);		
			 mydata[i]=keywordg;	
			//alert(keyword[i]["block"]);					
			}
	    setkeyword(mydata); 
			
		}		
		function pol_onDataReceived(json_data)
		{
		//	g_policy_data = json_data.policy;
			filldata_policy(json_data.policy);					
		}	
});
//init webcat table;
//jQuery(document).ready(function() {init_web(0);});
</script>
<script type="text/javascript">
	$(function(){

				// Accordion
				$("#accordion").accordion({ header: "h3" });
	
				// Tabs
				$('#tabs').tabs();
	

				// Dialog			
				$('#dialog').dialog({
					autoOpen: false,
					width: 600,
					buttons: {
						"Ok": function() { 
							$(this).dialog("close"); 
						}, 
						"Cancel": function() { 
							$(this).dialog("close"); 
						} 
					}
				});
				
				// Dialog Link
				$('#dialog_link').click(function(){
					$('#dialog').dialog('open');
					return false;
				});		
			});	
</script>
<script>
/*
 * 时间设置的DIV显示或者隐藏控制
 */
function time_control()
{

	var time_control_checkbox = document.getElementById("time_open");
	var time_control_div = document.getElementById("time_set_mini");
	if(time_control_checkbox.checked == true)
	{
		time_control_div.disabled=false;
	}
	else
	{
		time_control_div.disabled=true;
	}
}
function check_input()
{
	var sr = jQuery("#policy_list").getGridParam('selrow');
	var rowData = jQuery("#policy_list").getRowData(sr);
	var pid = rowData["id"];

	var ts1=$("#time_start1").val();
	var te1=$("#time_end1").val();
	var ts2=$("#time_start2").val();
	var te2=$("#time_end2").val();

/*	if(ts1.split(":")[0]==""||ts1.split(":")[1]==""||te1.split(":")[0]==""||te1.split(":")[1]==""||
			ts2.split(":")[0]==""||ts2.split(":")[1]==""||te2.split(":")[0]==""||te2.split(":")[1]=="")
	{
		alert("您输入的时间格式有误，请重新输入！");
		return false;
	}*/
	var ts1_hours=parseInt(ts1.split(":")[0]);
	var ts1_second=parseInt(ts1.split(":")[1]);
	var te1_hours=parseInt(te1.split(":")[0]);
	var te1_second=parseInt(te1.split(":")[1]);

	var ts2_hours=parseInt(ts2.split(":")[0]);
	var ts2_second=parseInt(ts2.split(":")[1]);
	var te2_hours=parseInt(te2.split(":")[0]);
	var te2_second=parseInt(te2.split(":")[1]);
	if(ts1_hours>te1_hours||ts2_hours>te2_hours)
	{
		alert("策略结束时间必须大于开始时间，请重新输入！");
		return false;
	}
	if(ts1_hours==te1_hours||ts2_hours==te2_hours)
	{
		if(ts1_second>te1_second||ts2_second>te2_second)
		{
			alert("策略结束时间必须大于开始时间，请重新输入！");
			return false;
		}
	}
	var tf = $("#time_open").attr("checked")==true?"1":"0";
	ts1 = ts1_hours*100 + ts1_second;
	te1 = te1_hours*100 + te1_second; 
	ts2 = ts2_hours*100 + ts2_second;
	te2 = te2_hours*100 + te2_second;
	var i=0;
	var wk="0";
	for(i=0;i<7;i++)
	{
		var day = "#d"+i;
		if($(day).attr("checked")==true)
		{
			wk = wk+"1";
		}
		else
		{
			wk = wk+"0";
		}		
	}
	week = parseInt(wk,2);
	
	var info_pro="";
	for(i=0;i<256;i++)
	{
		var pro = "#pro_id_"+i;
		if($(pro).attr("checked")==true)
		{
			info_pro = info_pro+i+"|";
		}		
	}
	var count = $("#web_list").getGridParam("reccount");
	var webfilter = $("#webfilter").attr("checked")==true?"1":"0";
	var webcat="";
	for(i=1;i<=count;i++)
	{
		var wcpass = "#wcpass"+i;
		var wclog = "#wclog"+i;
		var val=0;
		if($(wcpass).attr("checked")==true || $(wclog).attr("checked")==true)
		{
			if($(wclog).attr("checked")==true)
				val= 1;
			if($(wcpass).attr("checked")==true)
				val=parseInt(val)+2;
			webcat=webcat+i+","+val+"|";			
		}
	}
	count = $("#fc_list").getGridParam("reccount");
	var filefilter = $("#filetypefilter").attr("checked")==true?"1":"0";
	var filecat="";

	for(i=1;i<=count;i++)
	{
		var fcpass = "#fcpass"+i;
		var fclog = "#fclog"+i;
		var val=0;
		if($(fcpass).attr("checked")==true || $(fclog).attr("checked")==true)
		{
		/*	var p1 = $(fc).attr("checked")==true?"1":"0";
			var p2 = $(fclog).attr("checked")==true?"1":"0";
			filecat = filecat+i+","+p1+","+p2+"|";*/
			if($(fclog).attr("checked")==true)
				val = 1;
			if($(fcpass).attr("checked")==true)
				val = parseInt(val)+2;
			filecat = filecat+i+","+val+"|";
		}
	}
	//add keywordinfo here
	count = $("#keyword_list").getGridParam("reccount");
	var utf = "";
	var gb = "";
	for(i=0;i<count;i++)
	{
		var rowData = jQuery("#keyword_list").getRowData(i);
		var id_b = "#kwb_"+i;
		var id_l = "#kwl_"+i;
		var val=0;

		if($(id_l).attr("checked")==true)
		{
			val = 1;
		}
		if($(id_b).attr("checked")==true)
		{
			val = parseInt(val)+2;
		}
		utf = utf + rowData['keyword']+","+val+"|";
		gb = gb + rowData['keyword']+","+val+"|";
		//switch(rowData['keyword']);
	//	jQuery("#keyword_list").jqGrid('addRowData',i+1,mydata[i]);
	}
	var kf = $("#keywordfilter").attr("checked")==true?"1":"0";
	var smtp = $("#audit_smtp").attr("checked")==true?"1":"0";
	var pop3 = $("#audit_pop3").attr("checked")==true?"1":"0";
	var post = $("#audit_post").attr("checked")==true?"1":"0";
	
	$.post("policy_save.php",{pid:pid,tf:tf,week:week,ts1:ts1,te1:te1,ts2:ts2,te2:te2,pi:info_pro,wf:webfilter,
		wi:webcat,ff:filefilter,fi:filecat,kf:kf,kwutf:utf,kwgb:gb,smtp:smtp,pop3:pop3,post:post
		});
}

//web_jqgrid

jQuery(document).ready(function(){
jQuery("#web_list").jqGrid({
    url:'web_catagory_data.php',
	editurl:'',
    datatype: "json",
	colNames:['ID','网站分类','阻挡','记录日志'],
    colModel:[
		{name:'webid',index:'webid', width:100,align:"center"},
		{name:'name',index:'name',width:150,align:"center"},    
		{name:'pass',index:'pass', width:100,align:"center"},
		{name:'log',index:'log', width:100,align:"center"}
   	],
	//pager: jQuery('#web_pager'),
    rowNum:55,
    width:650,
    rowList:[55,100],
    //imgpath: 'themes/basic/images',
 	multiselect:false,
 //	hiddengrid:true,
    sortname: 'webid',
    viewrecords: true,
    sortorder: "asc",
    caption: "网站分类列表",
    gridComplete:function()
	    {
   		//	 alert("good");
		//	filldata_policy(g_policy_data);
	    }
	});
	

//	jQuery("#blackweb_list").jqGrid('navGrid','#web_pager',{edit:false,add:false,del:false,search:false});
});
//filecat 
jQuery(document).ready(function(){
	jQuery("#fc_list").jqGrid({
	    url:'fc_catagory_data.php',
		datatype: "json",
		colNames:['ID','文件类型','阻挡','记录日志'],
	    colModel:[
			{name:'typeid',index:'typeid', width:100,align:"center"},
			{name:'name',index:'name',width:150,align:"center"},    
			{name:'pass',index:'pass', width:100,align:"center"},
			{name:'log',index:'log', width:100,align:"center"}
	   	],
	//	pager: jQuery('#fc_pager'),
	    rowNum:55,
	    width:650,
	    rowList:[55,100],
	    //imgpath: 'themes/basic/images',
	 	multiselect:false,
	// 	hiddengrid:true,
	    sortname:'typeid',
	    viewrecords: false,
	    sortorder: "asc",
	    caption: "文件类型列表"
		});
//		jQuery("#blackweb_list").jqGrid('navGrid','#web_pager',{edit:false,add:false,del:false,search:false});
});
//关键词
jQuery(document).ready(function(){
    jQuery("#keyword_list").jqGrid({
    editurl:'none.php',
    datatype: "json",
	 colNames:['关键词','阻挡','日志'],
    colModel:[
  	   	{name:'keyword',index:'keyword', width:150,editable:true,editrules:{required:true}},
   		{name:'block',index:'block', width:150,editable:true,editrules:{required:true},edittype:"checkbox",editoptions:{value:"1:0"}},
   		{name:'log',index:'log', width:100, align:"right",editable:true,editrules:{required:true},edittype:"checkbox",editoptions:{value:"1:0"}},
   	],
	width:650,
//	hiddengrid:true,
    pager: jQuery('#keyword_pager'),
    caption: "关键词"
});

  jQuery("#keyword_list").jqGrid('navGrid','#pager',{edit:false,edittext:'编',add:true,addtext:'增', del:true, deltext:'删',search:false},
		{//edit 
		     //jQuery("#keyword_list").jqGrid('editGridRow',"new",{height:280, reloadAfterSubmit:false}),
           closeAfterAdd: true, 
           closeAfterEdit: true          	
      },  
        {//add
    	  beforeSubmit:function(postdata, formid) 
		  { 
            // jQuery("#keyword_list").jqGrid('editGridRow',"new",{height:280, reloadAfterSubmit:false});
            //  jQuery("#keyword_list").jqGrid('addRowData',i,mydata[i]);     
		    	var count = $("#keyword_list").getGridParam("reccount");
		    	if(count>=10)
		    		return[false,'每天策略最多10个关键字'];
		    	var len = postdata['keyword'].length;
	    		if(len>=64)
	    		{
	    			return[false,'关键字长度超过最大范围'];
	    		}
	    		for(var i=0;i<len;i++)
	    		{
		    		var char = postdata['keyword'].charAt(i);
	    			if(char ==',' || char=='|')
		    		{
	    				return[false,'","和"|"非法字符'];
		    		}
	    		}
	    	/*	for(var i=0;i<postdata['keyword'].length;i++)
	    		{
		    		alert(postdata['keyword'][i]);
	    		}*/
		    	var mydata = new Array();
		    	var i =0;
		  		for(i=0;i<count;i++)
		  		{
		  			var rowData = jQuery("#keyword_list").getRowData(i);
		            var keywordg = new Array();
					keywordg["keyword"]=rowData['keyword'];				
				  	keywordg['block'] = rowData['block'];
					keywordg['log'] = rowData['log'];
					mydata[i]=keywordg;
		  		}
				var tmpdata= new Array();
				tmpdata['keyword'] = postdata['keyword'];
				if(postdata['block']=='1')
					tmpdata['block'] = "<input id='kwb_"+i+"' type='checkbox' checked='checked' />";
				else
					tmpdata['block'] = "<input id='kwb_"+i+"' type='checkbox' />";
				if(postdata['log']=='1')
					tmpdata['log'] = "<input id='kwl_"+i+"' type='checkbox' checked='checked' />";
				else
					tmpdata['log'] = "<input id='kwl_"+i+"' type='checkbox' />";
				mydata[i]=tmpdata;
				jQuery("#keyword_list").clearGridData();
				setkeyword(mydata); 
				return[true,'OK'];
           },
            closeAfterAdd: true, 
            closeAfterEdit: true   		
   	 },
		{//DEL
			onclickSubmit:function(postdata, formid) 
			{
				var sr = jQuery("#keyword_list").getGridParam('selrow');
           		jQuery("#keyword_list").jqGrid('delRowData',sr);   
           
			}
		});
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
<h1>所有策略列表</h1>
<table id="policy_list" class="scroll" cellpadding="0" cellspacing="0"></table>
<!-- pager definition. class scroll tels that we want to use the same theme as grid -->
<div id="pager" class="scroll" style="text-align:center;"></div>
<br/>
<!-- Tabs -->
<h1>单条策略详细信息</h1>
		<div id="tabs">
			<ul>
				<li><a href="#tabs-1">时间</a></li>
				<li><a href="#tabs-2">协议控制</a></li>
				<li><a href="#tabs-3">网页控制</a></li>
				<li><a href="#tabs-4">审计设置</a></li>
			</ul>
			<div id="tabs-1">
			    <h2>开关</h2>
				     &nbsp&nbsp<input type="checkbox" name="time_open" id="time_open" value=1 onclick="time_control()">是否启用
				<div id="time_set_mini" >   
					<h2>您希望本策略在一天中的什么时间执行：</h2>
					<table>
					  <tr>
						<td align="right"><span class="fontRed">*</span> 开始时间1:</td>				
						<td>
							<select id="time_start1" style="width:80px;height:30px;position:relative;left:0px;" >  
							<option value="00:00">00:00</option><option value="00:30">00:30</option><option value="01:00">01:00</option>   
							<option value="01:30">01:30</option><option value="02:00">02:00</option><option value="02:30">02:30</option>  
							<option value="03:00">03:00</option><option value="03:30">03:30</option><option value="04:00">04:00</option>
							<option value="04:30">04:30</option><option value="05:00">05:00</option><option value="05:30">05:30</option>
							<option value="06:00">06:00</option><option value="06:30">06:30</option><option value="07:00">07:00</option>
							<option value="07:30">07:30</option><option value="08:00">08:00</option><option value="08:30">08:30</option>   
							<option value="09:00">09:00</option><option value="09:30">09:30</option><option value="10:00">10:00</option>
							<option value="10:30">10:30</option><option value="11:00">11:00</option><option value="11:30">11:30</option>
							<option value="12:00">12:00</option><option value="12:30">12:30</option><option value="13:00">13:00</option> 
							<option value="13:30">13:30</option><option value="14:00">14:00</option><option value="14:30">14:30</option>
							<option value="15:00">15:00</option><option value="15:30">15:30</option><option value="16:00">16:00</option>
							<option value="16:30">16:30</option><option value="17:00">17:00</option><option value="17:30">17:30</option>
							<option value="18:00">18:00</option><option value="18:30">18:30</option><option value="19:00">19:00</option>
							<option value="19:30">19:30</option><option value="20:00">20:00</option><option value="20:30">20:30</option>
							<option value="21:00">21:00</option><option value="21:30">21:30</option><option value="22:00">22:00</option>
							<option value="22:30">22:30</option><option value="23:00">23:00</option><option value="23:30">23:30</option>
							<option value="24:00">24:00</option>
							</select>				
						</td>
						<td align="right"><span class="fontRed">*</span> 结束时间1:</td>
						<td>
							<select id="time_end1" style="width:80px;height:30px;position:relative;left:0px;">  
							<option value="00:00">00:00</option><option value="00:30">00:30</option><option value="01:00">01:00</option>   
							<option value="01:30">01:30</option><option value="02:00">02:00</option><option value="02:30">02:30</option>  
							<option value="03:00">03:00</option><option value="03:30">03:30</option><option value="04:00">04:00</option>
							<option value="04:30">04:30</option><option value="05:00">05:00</option><option value="05:30">05:30</option>
							<option value="06:00">06:00</option><option value="06:30">06:30</option><option value="07:00">07:00</option>
							<option value="07:30">07:30</option><option value="08:00">08:00</option><option value="08:30">08:30</option>   
							<option value="09:00">09:00</option><option value="09:30">09:30</option><option value="10:00">10:00</option>
							<option value="10:30">10:30</option><option value="11:00">11:00</option><option value="11:30">11:30</option>
							<option value="12:00">12:00</option><option value="12:30">12:30</option><option value="13:00">13:00</option> 
							<option value="13:30">13:30</option><option value="14:00">14:00</option><option value="14:30">14:30</option>
							<option value="15:00">15:00</option><option value="15:30">15:30</option><option value="16:00">16:00</option>
							<option value="16:30">16:30</option><option value="17:00">17:00</option><option value="17:30">17:30</option>
							<option value="18:00">18:00</option><option value="18:30">18:30</option><option value="19:00">19:00</option>
							<option value="19:30">19:30</option><option value="20:00">20:00</option><option value="20:30">20:30</option>
							<option value="21:00">21:00</option><option value="21:30">21:30</option><option value="22:00">22:00</option>
							<option value="22:30">22:30</option><option value="23:00">23:00</option><option value="23:30">23:30</option>
							<option value="24:00">24:00</option>
							</select>
						</td>
					 </tr>
					 <tr>	
						<td align="right"><span class="fontRed">*</span> 开始时间2:</td>
						<td>
							<select id="time_start2" style="width:80px;height:30px;position:relative;left:0px;">  
							<option value="00:00">00:00</option><option value="00:30">00:30</option><option value="01:00">01:00</option>   
							<option value="01:30">01:30</option><option value="02:00">02:00</option><option value="02:30">02:30</option>  
							<option value="03:00">03:00</option><option value="03:30">03:30</option><option value="04:00">04:00</option>
							<option value="04:30">04:30</option><option value="05:00">05:00</option><option value="05:30">05:30</option>
							<option value="06:00">06:00</option><option value="06:30">06:30</option><option value="07:00">07:00</option>
							<option value="07:30">07:30</option><option value="08:00">08:00</option><option value="08:30">08:30</option>   
							<option value="09:00">09:00</option><option value="09:30">09:30</option><option value="10:00">10:00</option>
							<option value="10:30">10:30</option><option value="11:00">11:00</option><option value="11:30">11:30</option>
							<option value="12:00">12:00</option><option value="12:30">12:30</option><option value="13:00">13:00</option> 
							<option value="13:30">13:30</option><option value="14:00">14:00</option><option value="14:30">14:30</option>
							<option value="15:00">15:00</option><option value="15:30">15:30</option><option value="16:00">16:00</option>
							<option value="16:30">16:30</option><option value="17:00">17:00</option><option value="17:30">17:30</option>
							<option value="18:00">18:00</option><option value="18:30">18:30</option><option value="19:00">19:00</option>
							<option value="19:30">19:30</option><option value="20:00">20:00</option><option value="20:30">20:30</option>
							<option value="21:00">21:00</option><option value="21:30">21:30</option><option value="22:00">22:00</option>
							<option value="22:30">22:30</option><option value="23:00">23:00</option><option value="23:30">23:30</option>
							<option value="24:00">24:00</option>
							</select>
						</td>					
						<td align="right"><span class="fontRed">*</span> 结束时间2:</td>
						<td>
							<select id="time_end2" style="width:80px;height:30px;position:relative;left:0px;">  
							<option value="00:00">00:00</option><option value="00:30">00:30</option><option value="01:00">01:00</option>   
							<option value="01:30">01:30</option><option value="02:00">02:00</option><option value="02:30">02:30</option>  
							<option value="03:00">03:00</option><option value="03:30">03:30</option><option value="04:00">04:00</option>
							<option value="04:30">04:30</option><option value="05:00">05:00</option><option value="05:30">05:30</option>
							<option value="06:00">06:00</option><option value="06:30">06:30</option><option value="07:00">07:00</option>
							<option value="07:30">07:30</option><option value="08:00">08:00</option><option value="08:30">08:30</option>   
							<option value="09:00">09:00</option><option value="09:30">09:30</option><option value="10:00">10:00</option>
							<option value="10:30">10:30</option><option value="11:00">11:00</option><option value="11:30">11:30</option>
							<option value="12:00">12:00</option><option value="12:30">12:30</option><option value="13:00">13:00</option> 
							<option value="13:30">13:30</option><option value="14:00">14:00</option><option value="14:30">14:30</option>
							<option value="15:00">15:00</option><option value="15:30">15:30</option><option value="16:00">16:00</option>
							<option value="16:30">16:30</option><option value="17:00">17:00</option><option value="17:30">17:30</option>
							<option value="18:00">18:00</option><option value="18:30">18:30</option><option value="19:00">19:00</option>
							<option value="19:30">19:30</option><option value="20:00">20:00</option><option value="20:30">20:30</option>
							<option value="21:00">21:00</option><option value="21:30">21:30</option><option value="22:00">22:00</option>
							<option value="22:30">22:30</option><option value="23:00">23:00</option><option value="23:30">23:30</option>
							<option value="24:00">24:00</option>
							</select>
						</td>					
					  </tr>
					</table>
						<br>
					<h2>您希望本策略在一周中的星期几执行：</h2>
					&nbsp&nbsp
					<table>
					<tr>
					<td><input type="checkbox" name="week_set[]" id="d0" value=1 <?php echo $week_monday?> >星期一</td>
					<td><input type="checkbox" name="week_set[]" id="d1" value=2 <?php echo $week_tuesday?> >星期二</td>
					<td><input type="checkbox" name="week_set[]" id="d2" value=4 <?php echo $week_wednesday?> >星期三</td>
					<td><input type="checkbox" name="week_set[]" id="d3" value=8 <?php echo $week_thursday?> >星期四</td>
					<td><input type="checkbox" name="week_set[]" id="d4" value=16 <?php echo $week_friday?>>星期五</td>
					<tr>
					<tr>
					<td><input type="checkbox" name="week_set[]" id="d5" value=32 <?php echo $week_saturday?> >星期六</td>
				    <td><input type="checkbox" name="week_set[]" id="d6" value=64 <?php echo $week_sunday?> >星期天</td>
					</tr>
					</table>
				</div>
			</div>
			<div id="tabs-2">
			<h2>您希望本策略阻挡哪些协议：</h2>
			 <?php 
			  $result = $db->query2("select * from procat where proid= -1 order by type","M",false);
			  foreach($result as $row)
			  {
			    echo "<table><tr>";
			    echo "<td height='20px'><input type='checkbox' id =".  "check_all_" . $row['type']  ." onclick= 'on_select(&quot;" . $row['type']. "&quot;)' /></td>";
				echo "<td height='20px'><a href = '#tabs-2' onclick='onShowHide(&quot;div_id_" . $row['type'] . "&quot;)'>" .  $row['name'] . "</a></td>";
				echo "</tr></table>";
				echo "<div style='vertical-align:middle' id = '" . "div_id_" . $row['type'] . "'></div>"; 
			  }
              ?>
			</div>
			<div id="tabs-3" style="height:1000px">
					<h1></h1>
					<h1></h1>
					<div>
						<table><tr><td valign = 'middle'>网站分类</td><td valign='middle'>&nbsp;&nbsp;<input type="checkbox" name="wc" id="webfilter" value="1" onclick="#">是否启用</td></tr></table>
						<div>
						<h2>您希望本策略阻挡哪些网站类型：</h2>
						<div id="webcat" >
						<table id="web_list" class="scroll" cellpadding="0" cellspacing="0"></table>
						<!-- pager definition. class scroll tels that we want to use the same theme as grid -->
						<div id="web_pager" class="scroll" style="text-align:center;"></div>
						</div>
						</div>
					</div>
					<h1></h1>
					<h1></h1>
					<div>
					    <table><tr><td>文件类型</td><td>&nbsp;&nbsp;<input type="checkbox" name="wc" id="filetypefilter" value="1" onclick="#">是否启用</td></tr></table>
						<div>
						<h2>您希望本策略阻挡哪些文件类型：</h2>
						<div id="filecat" >
						<table id="fc_list" class="scroll" cellpadding="0" cellspacing="0"></table>
						<!-- pager definition. class scroll tels that we want to use the same theme as grid -->
						<div id="fc_pager" class="scroll" style="text-align:center;"></div>
						</div>
						</div>
					</div>
					<h1></h1>
					<h1></h1>
					<div>
						<table><tr><td>关键词</td><td>&nbsp;&nbsp;<input type="checkbox" name="wc" id="keywordfilter" value="1" onclick="#">是否启用</td></tr></table>
						<div>
						<h2>您希望为本策略添加哪些关键词：</h2>
				       	<table id="keyword_list" class="scroll" cellpadding="0" cellspacing="0"></table>
						<div id="keyword_pager" class="scroll" style="text-align:center;"></div>
						</div>
					</div>
			</div>
			<div id="tabs-4">
				<h2>开关,请在需要审计的项目前打钩：</h2>
					&nbsp&nbsp<input type="checkbox" name="audit[]" id="audit_smtp" value="1" <?php echo $audit_smtp;?>>客户端发送邮件<br/><br/>
					&nbsp&nbsp<input type="checkbox" name="audit[]" id="audit_pop3" value="2" <?php echo $audit_pop3;?>>客户端接收邮件<br/><br/>
					&nbsp&nbsp<input type="checkbox" name="audit[]" id="audit_post" value="3" <?php echo $audit_post;?>>网页发送邮件及发帖审计<br/><br/>
			</div>
		</div>
<!-- <input type="submit" name="Submit3" style="width:70px;height:30px;float:left; margin-left:300px;" value="提交" onclick="return check_input();"> 
<input type="button" style="width:70px;height:30px; "  name="cancel" value="取消" onclick="cancel_submit();">  
</form>-->
<input type="button" style="width:70px;height:30px; "  name="submit" value="取消" onclick="return check_input();">

<script>
senfe("wc_list","#f8fbfc","#e5f1f4","#ecfbd4","#bce774");
senfe("fc_list","#f8fbfc","#e5f1f4","#ecfbd4","#bce774");
</script>
</body>
</html>

