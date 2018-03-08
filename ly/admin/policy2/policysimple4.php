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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>网站和网络软件等管理</title>
<link href="./css/style.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css" href="./css/standalone.css"/>
<!--link href="./css/skin1.css" rel="stylesheet" type="text/css"/-->
	<!-- style for the range inputs only --> 
	<link rel="stylesheet" type="text/css" href="./css/small.css"/> 
	
	<!-- style for the "surroundings" --> 
	<link rel="stylesheet" type="text/css" href="./css/multiple.css"/> 
	
	<!-- IE6 tweaks are needed. Didn't bother finish this. sorry. --> 
	<!--[if lt IE 7]>
	<style>
	#controls { background-image:none; } 
	.vertical { width:120px; }
	</style> 
	<![endif]--> 

<script type='text/javascript' src='../js/jquery.js'></script>
<script type="text/javascript" src="./js/example.js"></script>
<style type="text/css" title="currentStyle">
	@import "./css/demo_page.css";
	@import "./css/demo_table.css";
</style>

<style> 
body {padding:0;}
#scroll {
	position:relative;
	overflow:hidden;
	border:1px solid #ddd;
	width:948px;
	padding:10px;
	height:610px;
	-moz-border-radius:5px;
	-webkit-border-radius:5px;
}
 
#tools {
	width:9999em;
	position:absolute;
	height:400px;
}
 
.tool {
	float:left;
	width:1000px;
	height:340px;
	text-align:center;
}
 
.details {
	font-size:18px;
	color:#555;
	margin-top:-10px;
	background-color:transparent;
	padding:5px 100px;
}
 
 
#thumbs {
	background:url(./images/navi.jpg) no-repeat;
	height:80px;
	position:absolute;
	top:530px;
	width:990px;
	left:-8px;
}
 
.t {
	padding:0 !important;
	border:0 !important;
}
 
.t a {
	background:transparent url(./images/navi.jpg) no-repeat scroll -21px -90px;
	margin-left:11px;
	display:block;
	width:99px;
	float:left;
	height:90px;
	cursor:pointer;
}
 
.t a.active {
	cursor:default !important;
}
 
.navi {
	margin-left:314px;
	_margin-left:304px;
}
 
/* CSS sprite for the navigation */
#t0 		  { margin-left:20px; _margin-left:10px;}
#t0.active { background-position:-21px 0 !important; }
#t0:hover  { background-position:-21px -180px; }
#t0:active { background-position:-21px -270px; }
 
#t1			{ background-position:-325px -90px; }
#t1:hover 	{ background-position:-325px -180px; }
#t1:active	{ background-position:-325px -270px; }
#t1.active	{ background-position:-325px 0 !important; }
 
#t2			{ background-position:-435px -90px; }
#t2:hover 	{ background-position:-435px -180px; }
#t2:active	{ background-position:-435px -270px; }
#t2.active	{ background-position:-435px 0 !important; }
 
#t3			{ background-position:-545px -90px; }
#t3:hover 	{ background-position:-545px -180px; }
#t3:active	{ background-position:-545px -270px; }
#t3.active	{ background-position:-545px 0 !important; }
 
#t4			{ background-position:-655px -90px; }
#t4:hover 	{ background-position:-655px -180px; }
#t4:active	{ background-position:-655px -270px; }
#t4.active	{ background-position:-655px 0 !important; }
 
#t5			{ background-position:-765px -90px; }
#t5:hover 	{ background-position:-765px -180px; }
#t5:active	{ background-position:-765px -270px; }
#t5.active	{ background-position:-765px 0 !important; }
 
#t6			{ background-position:-875px -90px; }
#t6:hover 	{ background-position:-875px -180px; }
#t6:active	{ background-position:-875px -270px; }
#t6.active	{ background-position:-875px 0 !important; }


</style>


<script type="text/javascript" language="javascript" src="./js/jquery.tools.min.js"></script>
<script type="text/javascript" language="javascript" src="./js/jquery.dataTables.min.js"></script>



<script type="text/javascript" charset="utf-8">
jQuery(document).ready(function(){
	function onDataReceived(json)
	{
	   g_json_select = json;
	
	   /*for(var item in json)
	   {
    	  var x = "#div_id_"+item;
		  var length = json[item].length;
		  for(var i =0;i<length;i++)
		  {
			  var input = "<input id ='pro_id_" +json[item][i].proid +"' type='checkbox' />"+json[item][i].name+"";
			  $(x).append(input);
		  }
		  $(x).append("<h2></h2>");
		  $(x).hide();
	   }*/
	}
	
	$.ajax({
        url: "./catagory_data.php",
		cache:false,
        method: 'GET',
        dataType: 'json',
        success: onDataReceived
	});
});
	$(document).ready(function() {
		$('#web_base').dataTable( {
			"bPaginate": false,
			"bLengthChange": false,
			"bFilter": false,
			"bSort": false,
			"bInfo": false,
			"bAutoWidth": false } );
	} );
	$(document).ready(function() {
		$('#protocol_base').dataTable( {
			"bPaginate": true,
			"bLengthChange": false,
			"bFilter": false,
			"bSort": false,
			"bInfo": false,
			"bAutoWidth": false } );
	} );
	$(document).ready(function() {
		$('#file_table').dataTable( {
			"bPaginate": true,
			"bLengthChange": false,
			"bFilter": false,
			"bSort": false,
			"bInfo": false,
			"bAutoWidth": false } );
	} );

	
</script>
<script type="text/javascript" charset="utf-8">
//关键词增加修改
	var oTable;
	var g_keyword = []; //所有关键词
	
	$(document).ready(function() {
		$('#keyword_table tbody td').click( function () { 
 		} ); 
 
	
		oTable = $('#keyword_table').dataTable(
		{
			"bPaginate": true,
			"bLengthChange": false,
			"bFilter": false,
			"bSort": false,
			"bInfo": false,
			"bAutoWidth": false });
	} );
	//关键词， 在table中是哪一行 ，他的值是多少
	function add_keyword(keyword,index,value)
	{
	   g_keyword[keyword] = [index,value];
	   //g_keyword.push([keyword,index,value]);
	}	
    function modify_keyword(keyword,value)
	{
	   var obj = g_keyword[keyword];
	   if(obj)
	   {
	      g_keyword[keyword] = [obj[0],value];
		  
	   }
	}
	function delete_keyword(keyword)
	{
	 //var aData = oTable.fnGetData();
	   if(!confirm('确定将此关键词'+keyword+'删除?'))
       {
	      return false;
	   }
	   var obj = g_keyword[keyword];
	   if(obj)
	   {
			oTable.fnDeleteRow(parseInt(obj[0]));	   
			delete g_keyword[keyword];
	   }
	   /*
	   for(var i =0;i<g_keyword.length;i++)
	   {
	      if(g_keyword[i][0]==keyword)
		  {
			 oTable.fnDeleteRow( parseInt(g_keyword[i][1]) ); 
		     g_keyword.splice(i,1);
			 break;
		  }
	   }*/
	}
	function fnClickAddRow() {
	    var gcount =0;
	    var key_text = $("#keyword_input").val();
		if(key_text=="" || key_text==" ")
		{
		   return ;
		}
		if (typeof(g_keyword[key_text]) != "undefined")
        {
		   alert("该关键词已经存在!");
		   return ;
		}
		var key_block = "<input type = 'checkbox' onclick='g_keywork_id_click(&quot;"+key_text+"&quot;)' checked='checked' id='keyword_b_"+key_text+ "' />";
		var key_log   = "<input type = 'checkbox' onclick='g_keywork_id_click(&quot;"+key_text+"&quot;)' checked='checked' id='keyword_l_"+key_text+ "' />";
		var key_delete = "<a href ='#' onclick='delete_keyword(&quot;"+ key_text+"&quot;)'>"+"删除"+"</a>";
		var trindex = oTable.fnAddData( [
			key_text,
            key_block,			
			key_log,
			key_delete
			] );
		add_keyword(key_text,trindex,3);
	}
</script>
<script type = "text/javascript">
   var g_filetype= [];
   var typeidfrom = 16;
   var typeidto =  16;//已经存在的文件类型有15中，用户自定义必须从16开始
   //from == to 则表明没有增加，否则增加了
   function add_filetype(filetype)
   {
      g_filetype.push(filetype);
   }
   function add_filetype_user(filetype)
   {
      for(var i = 0; i<g_filetype.length;i++)
	  {
	     if(g_filetype[i]==filetype)
		 {
		    alert("该文件类型已经存在!");
			return false;
		 }
	  }
	  add_filetype(filetype);
	  typeid ++;
   }
   
   
   
</script>
<script type="text/javascript">
//	var g_policy_data;
    var g_json_select;
	var g_web_id_value = [];
	var g_pro_id_value = [];
	var g_file_id_value = [];

	
	var div_web_now = "div_web_id_0";
	var div_pro_now = "div_pro_id_0";
	function onShowHide(div_id)
	{
		$("#"+div_web_now).hide();
		//$("#"+div_id)["fadeIn"]("slide",{ direction: 'right' });
	    $("#"+div_id).fadeIn("slow");
		div_web_now = div_id;
	}
    function onShowHide_pro(div_id)
	{
		$("#"+div_pro_now).hide();
		//$("#"+div_id)["fadeIn"]("slide",{ direction: 'right' });
	    $("#"+div_id).fadeIn("slow");
		div_pro_now = div_id;
	}
	
	
	function set_div_web_id(c1,value)
	{
	/*
	    var id = parseInt(c1);
		for(var i = 0;i<g_json_select['web'].length;i++)
		{   
		      //alert(g_json_select['web'][i][2]);
			  var c2 = parseInt(g_json_select['web'][i][2]);
		      if(id==c2)
			  {
				var input = "#webid_" +g_json_select['web'][i][0] ;
			    $(input).attr("checked",value);
			  }
		}
	    onShowHide("div_web_id_"+c1);*/
	}
	//////////////////////////////////////////////
	//2010-6-26 日新添，为了解决table翻页后复选框找不到的问题
	//每次点击复选框之后要将值保存起来！web和pro都是如此
	function g_pro_id_value_click(_proid,_typeid)
	{
	   var pro = "#proid_"+_proid;
	   var prob = "#proid_b_"+_typeid;
	   var prol = "#proid_l_"+_typeid;
	   
	   
	   var value = 0;
	   if(!$(pro).attr("checked"))//id并没有选中
			g_pro_id_value[_proid] = 0;
	   else
       {	
          var b = $(prob).attr("checked");
          var l = $(prol).attr("checked");
          if(b)
             value +=2;
          if(l) 
             value +=1; 		  
	      g_pro_id_value[_proid] = value;
	   }
	}
	
	function g_file_id_value_click(_fileid)
	{
	   var fileb = "#fileid_b_" +_fileid;
	   var filel = "#fileid_l_" +_fileid;
	   var value = 0;
	   var b = $(fileb).attr("checked");
	   var l = $(filel).attr("checked");
	   if(b)
	     value +=2;
	   if(l)
	     value +=1;
	   g_file_id_value[_fileid] = value; 
	}
	function g_keywork_id_click(_keyword)
	{
	   var keyb = "#keyword_b_"+_keyword;
	   var keyl = "#keyword_l_"+_keyword;
	   var value = 0;
	   var b = $(keyb).attr("checked");
	   var l = $(keyl).attr("checked");
	   if(b)
	      value +=2;
	   if(l)
	      value +=1;
       modify_keyword(_keyword,value);		  
	}

	//计算值
	function g_web_id_value_click(_webid,_typeid)
	{
	   var web = "#webid_"+_webid;
	   var webb = "#webid_b_"+_typeid;
	   var webl = "#webid_l_"+_typeid;
   	   var webt = "#webid_t_"+_typeid;

	   var value = 0;
	   if(!$(web).attr("checked"))//id并没有选中
			g_web_id_value[_webid] = 0;
	   else
       {	
          var b = $(webb).attr("checked");
          var l = $(webl).attr("checked");
	  	  var t = $(webt).attr("checked");
          if(b)
             value +=2;
          if(l) 
             value +=1; 	
		  if(t)
             value +=4;		  
	      g_web_id_value[_webid] = value;
	   }
	}
	///////////////////////////////////////////////////////
	/////////////////////////////////////////////////
	//类型，是否选中，是否子节点修改
	function on_select_web_detail(type,checked,needmodify)
	{
		for(var i = 0;i<g_json_select['web'].length;i++)
		{
	      var typein = parseInt(g_json_select['web'][i][2]);
	      if(type  == typein)
		  {
		    if(needmodify)
			{
				var input = "#webid_" +g_json_select['web'][i][0] ;
				//把所有子节点选上
				$(input).attr("checked",checked);
			}
			g_web_id_value_click(g_json_select['web'][i][0],type);
		  }
		}
	}
	function on_modify_typeid_webid_value(type)
	{
		for(var i = 0;i<g_json_select['web'].length;i++)
		{
	      var typein = parseInt(g_json_select['web'][i][2]);
	      if(type  == typein)
		  {
			 g_web_id_value_click(g_json_select['web'][i][0],type);
		  }
		}
	}
	//
	function on_select_web_all(type)
	{
	   var xall = "#webid_all_"+type;
	   var valueall = $(xall).attr("checked");
	   for(var i = 0;i<g_json_select['web'].length;i++)
	   {
	      var typein = parseInt(g_json_select['web'][i][2]);
	      if(type  == typein)
		  {
			var input = "#webid_" +g_json_select['web'][i][0] ;
				//把所有子节点选上
			$(input).attr("checked",valueall);
			//计算值，存入数组
			g_web_id_value_click(g_json_select['web'][i][0],type);
		  }
		}
	}
    function on_select_web(typeid,bl_click)
	{
		var div_id = typeid;
	    var xb = "#webid_b_" + typeid;//总开关
		var xl = "#webid_l_" + typeid;
		var xt = "#webid_t_" + typeid;   
		
		var valueb = $(xb).attr("checked");
		var valuel = $(xl).attr("checked");	
		var valuet = $(xt).attr("checked");
		
		var type = parseInt(typeid);
		
		if(bl_click == "b")
		{
		   if(valueb)
		   {
				$(xt).attr("checked",false);
		   }		
		}
		else if(bl_click == "t")
		{
		   if(valuet)
		   {
		      $(xb).attr("checked",false);
		   }
		}

		
		on_modify_typeid_webid_value(typeid);  
	    onShowHide("div_web_id_"+div_id);			
	
	}
  

	function on_select_pro_detail(type,checked,needmodify)
	{
		for(var i = 0;i<g_json_select['pro'].length;i++)
		{
	      var typein = parseInt(g_json_select['pro'][i][2]);
	      if(type  == typein)
		  {
		    if(needmodify)
			{
				var input = "#proid_" +g_json_select['pro'][i][0] ;
				//把所有子节点选上
				$(input).attr("checked",checked);
			}
			g_pro_id_value_click(g_json_select['pro'][i][0],type);
		  }
		}
	}
	
	function on_select_pro_all(type)
	{
	   var xall = "#proid_all_"+type;
	   var valueall = $(xall).attr("checked");
	   for(var i = 0;i<g_json_select['pro'].length;i++)
	   {
	      var typein = parseInt(g_json_select['pro'][i][2]);
	      if(type  == typein)
		  {
			var input = "#proid_" +g_json_select['pro'][i][0] ;
				//把所有子节点选上
			$(input).attr("checked",valueall);
			//计算值，存入数组
			g_pro_id_value_click(g_json_select['pro'][i][0],type);
		  }
		}

	}
	function on_modify_typeid_proid_value(type)
	{
		for(var i = 0;i<g_json_select['pro'].length;i++)
		{
	      var typein = parseInt(g_json_select['pro'][i][2]);
	      if(type  == typein)
		  {
			 g_pro_id_value_click(g_json_select['pro'][i][0],type);
		  }
		}
	}
    function on_select_pro(typeid,bl_click)
	{
	//typeid 实际上就是下面子类的层id
	    var div_id = typeid;
	    /*var xb = "#proid_b_" + typeid;//总开关
		var xl = "#proid_l_" + typeid;
		var valueb = $(xb).attr("checked");
		var valuel = $(xl).attr("checked");	*/
		var type = parseInt(typeid);
		on_modify_typeid_proid_value(type);
		onShowHide_pro("div_pro_id_"+div_id);
		
	
		
	}

    function set_checkbox(weborpro,id,number,type)
	{
	   if(weborpro=="web")
	   {
	      var b = "#webid_b_"+type;//2
		  var t = "#webid_t_"+type;//4
		  var l = "#webid_l_"+type;//1
		  var webid = "#webid_"+id;
	      if(number==1)
		  {//log
		     $(l).attr("checked",true);
		  }
		  else if(number ==2)
		  {//block
		     $(b).attr("checked",true);
		  }
          else if(number ==3)
		  {//log+block
		     $(l).attr("checked",true);
		     $(b).attr("checked",true);
		  }
		  else if(number == 4)
		  {
		     $(t).attr("checked",true);
		  }
		  else if(number == 5)
		  {
		     $(t).attr("checked",true);
		     $(l).attr("checked",true);
		  }
          $(webid).attr("checked",true); 		  
	      g_web_id_value[id] = number;
	   }
	   else if(weborpro=="pro")
	   {
	      var b = "#proid_b_"+type;
		  var l ="#proid_l_" +type;
		  var proid = "#proid_"+id;
		  if(number ==1)
		  {//log
		     $(l).attr("checked",true);
		  }
		  else if(number ==2)
		  {
		     $(b).attr("checked",true);
		  }
		  else if(number ==3)
		  {
		     $(l).attr("checked",true);
		     $(b).attr("checked",true);
		  }
		  //保存已经有的值！！！！！
		  $(proid).attr("checked",true); 
	      g_pro_id_value[id] = number;
	   }
	   else if(weborpro == "file")
	   {
	       var b = "#fileid_b_"+id;
		   var l = "#fileid_l_"+id;
		   if(number ==1)
		   {
		      $(l).attr("checked",true);
		   }
		   else if(number ==2)
		   {
		      $(b).attr("checked",true);
		   }
		   else if(number ==3)
		   {
		      $(l).attr("checked",true);
		      $(b).attr("checked",true);
		   }
		   g_file_id_value[id] = number;
	   }
	}
	
	
	function save_web()
	{
	
		var value = "";
		var has_value = 0;
		for(var row in g_web_id_value)
		{
		    var test = parseInt(g_web_id_value[row]);
		    if(test>0)
			{
				if(has_value>0)
				{
					value +="|";
				}
				else
				{
				   has_value = 1;
				}
				value += row+','+test;
			}
		}
		return value;
		/*
	   var value="";	
	   var has_value=0;
		for(var i = 0;i<g_json_select['web'].length;i++)
		{
		   //var x_block = "#"+"webid_"+g_json_select['web'][i][0];
		   var x_webid = "#"+"webid_"+g_json_select['web'][i][0];
		   var x_blo = "#"+"webid_b_"+g_json_select['web'][i][2];//阻断主类别的选择
		   var x_rem = "#"+"webid_t_"+g_json_select['web'][i][2];
    	   var x_log = "#"+"webid_l_"+g_json_select['web'][i][2];//日志主类别的选择
		   //log  1
		   //block  2
		   //log+block 3
		   if(!$(x_webid).attr("checked"))  // 子类是否选择
		      continue;
		   
		   var block =0;var log =0;
		   if($(x_blo).attr("checked"))
		     block = 2;
		   if($(x_log).attr("checked"))
		     log = 1;
		   var decisition = parseInt(log+block);
           if(decisition >0)		   
		   {
		      if(has_value>0)
			    value +="|";
    	      value += g_json_select['web'][i][0]+","+(decisition);
			  has_value = 1;
		   }
		}
        return value;	*/	
	}
	function save_pro()
	{
	    var value = "";
		var has_value = 0;
		for(var row in g_pro_id_value)
		{
		    var test = parseInt(g_pro_id_value[row]);
		    if(test>0)
			{
				if(has_value>0)
				{
					value +="|";
				}
				else
				{
				has_value = 1;
				}
				value += row+','+test;
			}
		}
		return value;
	}
	function save_filetype()
	{
	   var value="";
	   var has_value = 0;
       for(var row in g_file_id_value)
	   {
	        var test = parseInt(g_file_id_value[row]);
		    if(test>0)
			{
				if(has_value>0)
				{
					value +="|";
				}
				else
				{
					has_value = 1;
				}
				value += row+','+test;
			}
	   }
       return value;	   
	}
	
	/////////////////////////////////////////////////////////////
	////this is problem ，please modify this
	function save_keyword()
	{
	   var value ="";
	   var has_value =0;
	   var block  = 0;
	   for(var row in g_keyword)
	   {
	      var obj = g_keyword[row];
		  var test = parseInt(obj[1]);
		  if(has_value>0)
		  {
		     value +="|";
		  }
		  else
		  {
		     has_value = 1;
		  }
		  value += row+','+test;
	   }
/*	   var log =0;
	   for(var i =0;i<g_keyword.length;i++)
	   {
	      var x_blo = "#keyword_b_"+g_keyword[i][0];
		  var x_log = "#keyword_l_"+g_keyword[i][0];
		  if($(x_blo).attr("checked"))
			block =2;
		  if($(x_log).attr("checked"))
            log =1;
          var dec = parseInt(block+log);
          if(dec>0)
          {
		     if(has_value >0)
			 {
			    value +="|";
			 }
			 value += g_keyword[i][0]+","+dec;
			 has_value = 1;
          }		  
	   }*/
	   return value;
	}
	function save_audit_pop3()
	{
	   var x_audit = "#audit_pop3";
	   if($(x_audit).attr("checked"))
	   {
	      return 1;
	   }
	   return 0;
	}
	function save_audit_smtp()
	{
	   var x_audit = "#audit_smtp";
	   if($(x_audit).attr("checked"))
	   {
	      return 1;
	   }
	   return 0;
	}
	function save_audit_post()
	{
	   var x_audit = "#audit_post";
	   if($(x_audit).attr("checked"))
	   {
	      return 1;
	   }
	   return 0;
	}
	function save_time()
	{
	
	   
	}
    function save_week()
	{
	   var wk="";
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
		return week;
    }
	
	function savepolicy(policy_id)
	{
	    var xwebf =  ($("#web_if_use").attr("checked")==true)?1:0;
		var xfilef = ($("#file_if_use").attr("checked")==true)?1:0;
		var xkeyf  = ($("#keyword_if_use").attr("checked")==true)?1:0;
		var xtimef = ($("#time_if_use").attr("checked")==true)?1:0;
        var xweb  = save_web();
		var xpro  = save_pro();
		var xfile = save_filetype();
		var xkeyword = save_keyword();
		var smtp = save_audit_smtp();
		var post = save_audit_post();
		//alert("post:"+post);
		var pop3 =save_audit_pop3();
		//alert("web is:"+xweb);
		//alert("pro is:"+xpro);
		//alert(xfile);
		//alert(xkeyword);
		var week = save_week();
		var ts1_t = $("#ts1_t").attr("value");
		var ts1_m = $("#ts1_m").attr("value");
	    var te1_t = $("#te1_t").attr("value");
		var te1_m = $("te1_m").attr("value");
	    var ts2_t = $("#ts2_t").attr("value");
	    var ts2_m = $("#ts2_m").attr("value");
	    var te2_t = $("#te2_t").attr("value");
	    var te2_m = $("#te2_m").attr("value");
		var ts1 = parseInt(ts1_t*100)+parseInt(ts1_m);
		var te1 = parseInt(te1_t*100)+parseInt(te1_m);
		var ts2 = parseInt(ts2_t*100)+parseInt(ts2_m);
		var te2 = parseInt(te2_t)*100+parseInt(te2_m);
		//alert(t1f);
		//alert(t1t);
		//alert(t2f);
		//alert(t2t);
		//alert(xpro);
        $.post("policy_save.php",{pid:policy_id,tf:xtimef,week:week,ts1:ts1,te1:te1,ts2:ts2,te2:te2,pi:xpro,wf:xwebf,
		wi:xweb,ff:xfilef,fi:xfile,kf:xkeyf,kwutf:xkeyword,smtp:smtp,pop3:pop3,post:post
		});
		alert("策略已经被保存到数据库!");
	}

</script>
</head>
<body>
   <?php
      $policyid = $_GET['policyid'];
	  $SQL = "select * from policy where policyid = " . $policyid;
	  $res_policy = $db->query2one($SQL,"M",false);
	  $policyname   = $res_policy['name'];
	  $proctl = $res_policy['proctl'];
      $webfilter = $res_policy['webfilter'];
      $webctl = $res_policy['webinfo'];
  	  $filectl = $res_policy['fileinfo'];
      $filefilter = $res_policy['filetypefilter'];
	  $keyword       = $res_policy['keywordutf'];
	  $keywordfilter = $res_policy['keywordfilter'];
	  $timefilter    = $res_policy['time'];
	  $_use = "checked='checked'";
	  $_not_use = "";
	  $week_1 = "";
	  $week_2 = "";
	  $week_3 = "";
	  $week_4 = "";
	  $week_5 = "";
	  $week_6 = "";
	  $week_7 = "";
	  
	  $week  = $res_policy['week'];
	  if(($week&64)==64)
		$week_1 = $_use ;
	  if(($week&32)==32)
        $week_2 = $_use;
      if(($week&16)==16)
        $week_3 = $_use; 	  
      if(($week&8)==8)
        $week_4 = $_use; 	  
      if(($week&4)==4)
        $week_5 = $_use; 	  
      if(($week&2)==2)
        $week_6 = $_use; 	  
      if(($week&1)==1)
        $week_7 = $_use; 	  
	  
      $times1 = $res_policy['times1'];
	  $timee1 = $res_policy['timee1'];
	  $times2 = $res_policy['times2'];
	  $timee2 = $res_policy['timee2'];
	  $smtpaudit = $res_policy['smtpaudit'];
	  $pop3audit = $res_policy['pop3audit'];
	  $postaudit = $res_policy['postaudit'];
	$webid_d =array();
	$proid_d = array();
   ?>
   <div id="scroll">
 
	<!-- scrollable items -->
	<div id="tools">
		<!-- empty slot -->
		<div class="tool">&nbsp;</div>
		
				<!-- time setup -->
		<div class="tool">
			<div class="details">
				   <div class="menu"><h3>时间选项,选择策略生效的时间:</h3></div>
					<table><tr><td width=20><input type="checkbox" <?php if($timefilter==1) echo $_use; else echo $_not_use;?> id = "time_if_use"></td><td align='left' width =100>是否启用</td><tr><table>
					<h2></h2>
					<table><tr><td>您希望本策略在一天中的什么时间执行：</td></tr></table>
					<br>
					<p>
					<div id="controls">
					<?php
					   function get_t_m(&$x ,&$t,&$m)
					   {
							$m = $x%1000;
							$m = $m%100;
						    $t = ($x-$m)/100;
					   }
					   $s1_t=0; $s1_m = 0; $e1_t=0; $e1_m =0; 
					   $s2_t=0; $s2_m = 0; $e2_t=0; $e2_m =0; 
                       get_t_m($times1,$s1_t,$s1_m);
					 
					   get_t_m($timee1,$e1_t,$e1_m); //上午结束
				
					   get_t_m($times2,$s2_t,$s2_m);
					   
					   get_t_m($timee2,$e2_t,$e2_m); //下午结束
        					   
					?>
					  <table>
						<tr>
						  <td><input type="range" id = "ts1_t" min="0" max="23" value=<?php echo "'" .  $s1_t . "'"?>  /></td><td><font color="white">点</font></td>
						  <td><input type="range" id = "ts1_m" min="0" max="59" step ="1" value=<?php echo "'" . $s1_m . "'" ?>  /></td><td><font color="white">分</font></td>
						</tr>
						<tr>
						  <td><input type="range" id = "te1_t" min="0" max="23" value= <?php echo "'" . $e1_t . "'" ?>  /></td><td><font color="white">点</font></td>
						  <td><input type="range" id = "te1_m" min="0" max="59" step ="1" value= <?php echo "'" . $e1_m . "'" ?> /></td><td><font color="white">分</font></td>
						</tr>
						<tr>
						  <td><input type="range" id = "ts2_t" min="0" max="23" value= <?php echo "'" . $s2_t . "'" ?>  /></td><td><font color="white">点</font></td>
						  <td><input type="range" id = "ts2_m" min="0" max="59" step ="1" value= <?php echo "'" . $s2_m . "'" ?> /></td><td><font color="white">分</font></td>
						</tr>
						<tr>
						  <td><input type="range" id = "te2_t" min="0" max="23" value= <?php echo "'" . $e2_t . "'" ?>  /></td><td><font color="white">点</font></td>
						  <td><input type="range" id = "te2_m" min="0" max="59" step ="1" value= <?php echo "'" . $e2_m . "'" ?> /></td><td><font color="white">分</font></td>
						</tr>
                     </table>
					 </div>
  
						<!--input type="range" id ="time1f" name="test" min="0" max="2400" value=<?php //echo "'".$times1."'"?> / --> 
						<!--input type="range" id ="time1t" name="test" min="0" max="2400" value=<?php //echo "'" .$timee1 . "'"?> / --> 
						<!--input type="range" id ="time2f" name="test" min="0" max="2400" value=<?php //echo "'".$times2 . "'"?> / --> 
						<!--input type="range" id ="time2t" name="test" min="0" max="2400" value=<?php //echo "'" . $timee2 . "'"?> / --> 
						<script> 
							$(":range").rangeinput();
						</script> 
					<br>
					
					<h1></h1>
					<h2>您希望本策略在一周中的星期几执行：</h2>
                    <table> 
					<tr>
					<td align ='right' width ="20px"><input type="checkbox" name="week_set[]" id="d0" value=1 <?php echo $week_1?> ></td><td align ='left' width ="60px">星期一</td>
					<td align ='right' width ="20px"><input type="checkbox" name="week_set[]" id="d1" value=2 <?php echo $week_2?> ></td><td align ='left'width = "60px">星期二</td>
					<td align ='right' width ="20px"><input type="checkbox" name="week_set[]" id="d2" value=4 <?php echo $week_3?> ></td><td align ='left' width ="60px">星期三</td>
					<td align ='right' width ="20px"><input type="checkbox" name="week_set[]" id="d3" value=8 <?php echo $week_4?> ></td><td align ='left' width ="60px">星期四</td>
					<td align ='right' width ="20px"><input type="checkbox" name="week_set[]" id="d4" value=16 <?php echo $week_5?>></td><td align ='left' width ="60px">星期五</td>
					</tr>
					<tr>
					<td align ='right' width ="20px"><input type="checkbox" name="week_set[]" id="d5" value=32 <?php echo $week_6?> ></td><td align ='left' width ="60px">星期六</td>
				    <td align ='right' width ="20px"><input type="checkbox" name="week_set[]" id="d6" value=64 <?php echo $week_7?> ></td><td align ='left' width ="60px">星期天</td>
					</tr>
					</table>			
			</div><!-- detail end -->
  
		</div>
		
				<!-- protocol -->
		<div class="tool">
			<div class="details">
				 	<h3>您希望阻止或记录哪些类型的网络软件</h3>
					<?php
					   function get_catagory_pro_data($big_catagory,&$proid_d)
					   {
					        $db_dp = new Db();
							
							$SQL = "select proid,name,type from procat where proid<>-1 and type = " . $big_catagory . " order by type";
							$respro_detail= $db_dp->query2($SQL,"M",false);
							$i = 0;
							echo "<table align = 'left' border='0' cellspacing='0' cellpadding='0'><tr>";
							foreach($respro_detail as $row)
							{
								$proid_d[$row['proid']]=$big_catagory;
								if($i % 8 ==0)
								{
									echo "</tr><tr>";
								}
								echo "<td width ='20'><input type = 'checkbox' onclick='g_pro_id_value_click(&quot;". $row['proid'] ."&quot;,&quot;".$big_catagory."&quot;)' id = 'proid_" . $row['proid'] ."'/></td><td align='left' width='150'>" . $row['name'] ."</td>"; 
								$i++;
							}
							echo "</tr></table>";
					   }
					?>
					
					<table cellpadding="0" cellspacing="0" border="0" class="display" id="protocol_base">
					<thead><tr><th align='left'>类别</th><th>代码</th><th>阻挡</th><th>日志记录</th><th align = 'left'>描述</th></tr></thead>
					<tbody>
					<?php
						 $title_pro = array();
						 $title_pro_name =array();
						 $sql_cat ="select proid,name,type,description from procat where proid=0 or proid=-1 order by type";
					 	 $res_cat = $db->query2($sql_cat,"M",false);
						 foreach($res_cat as $row)
						 {
							$title_pro[] = $row['type'];
							$title_pro_name[]= $row['name'];
					?>
					<tr>
						<td align = 'left'><a href="#tabs" onclick="onShowHide_pro(<?php echo "'div_pro_id_".$row['type']."'"?>)"><?php echo $row['name']?></a></td>
						<td align = 'center'><?php echo $row['type']?></td>
						<td align = 'center'><input type=checkbox id = <?php echo "'proid_b_" . $row['type'] . "'"?> onclick= "on_select_pro(&quot;<?php echo $row['type']?>&quot;,'b')"></td>
						<td align = 'center'><input type=checkbox id = <?php echo "'proid_l_" . $row['type'] . "'"?> onclick= "on_select_pro(&quot;<?php echo $row['type']?>&quot;,'l')"></td>
						<td align = 'left'><?php echo $row['description']?></td>
					</tr>
                    <?php
						}
					?>
					</tbody></table>
					<div class = "all_pro">
					<?php
					   for($p_i=0;$p_i<count($title_pro);$p_i++)
					   {
					?>
					   <div align="center" id = <?php echo "'div_pro_id_" .$title_pro[$p_i] ."'" ?>>   
						  <table align="center"><tr><td><input type=checkbox id=<?php echo "'proid_all_".$title_pro[$p_i]."'" ?> onclick="on_select_pro_all(&quot;<?php echo $title_pro[$p_i]?>&quot;)"></td>
						  <td><b>所有<?php echo $title_pro_name[$p_i]?>的类别</b></td></tr></table>
					      <?php  get_catagory_pro_data($title_pro[$p_i],$proid_d)?> 
						  <h1></h1>
					   </div>
    				   <script>$("#div_pro_id_"+ <?php echo $title_pro[$p_i]?>).hide();</script>
					<?php   
					   }
					?>
					</div>
	                <script>$("#div_pro_id_"+ <?php echo $title_pro[0]?>).show();</script>

					<?php
					    function splitproctl($proctl,&$proid_d)
						 {
							$pieces = explode("|", $proctl);
							foreach($pieces  as $pi)
							{
								if($pi == "")
									continue;
								$ar = explode(",",$pi);
								if(!isset($ar[1])) // 因为有一个版本没有选择log还是block，只要是选择的就是log+block，因此赋值3
								   $ar[]="3";
					  	
                    ?>
					<script>
						//alert("this is:"+<?php echo $ar[0]?>+<?php echo $ar[1]?>+<?php echo $proid_d[$ar[0]] ?>)
						set_checkbox("pro",<?php echo $ar[0]?>,<?php echo $ar[1]?>,<?php echo $proid_d[$ar[0]] ?>);
					</script>		
                    <?php 
     						}	
						 }
					?>
					
                    <?php
					
					   if($proctl!="")
					   {
							splitproctl($proctl,$proid_d);	
					   }
					?>
					<?php
					   /*foreach($proid_d as $key=>$value)
					   {
							echo "pro " . $key . " is " .$value . "<br>";	
					   }*/
					?>		
			</div>
			</div><!-- tool end -->
		
		
		<!--this is the web -->
        <div class="tool">
			<div class="details">
					<h3>您希望阻止或记录哪些类型的网站</h3>
					<table><tr><td width=20><input type="checkbox" <?php if($webfilter==1) echo $_use; else echo $_not_use;?> id = "web_if_use"></td><td align='left' width =100>是否启用</td><tr><table>
					<h2></h2>
					<?php
					   function get_catagory_data($big_catagory,&$webid_d)
					   {
					        $db_d = new Db();
							
							//$sql_cout="select count(*) as count from webcat where type = " . $big_catagory;
							//$count=$db_d->fetchOne($sql_cout);
							$SQL  = "select webid,name,type from webcat where type = " . $big_catagory . " and webid<>-1 order by webid";	
							$i = 0;
							echo "<table align ='left' border='0' cellspacing='0' cellpadding='0'><tr>";
							$resweb_detail = $db_d->query2($SQL,"M",false);
							foreach($resweb_detail as $row)
							{
								$webid = $row['webid'];
								$webid_d[$webid]= $row['type'];//保存webid和主type类型的对应
								if($i % 9 ==0)
								{
									echo "</tr><tr>";
								}
								echo "<td width='20px'><input type = 'checkbox' onclick='g_web_id_value_click(&quot;" .$row['webid']."&quot;,&quot;" . $big_catagory . "&quot;)'". " id = 'webid_" . $row['webid'] ."'/></td><td align = 'left' width='150px'>" . $row['name'] ."</td>"; 
								$i++;
							}
							echo "</tr></table>";
					   }
					?>
					<table cellpadding="0" cellspacing="0" border="0" class="display" id="web_base">
					<thead><tr><th align='left'>类别</th><th>代码</th><th>阻挡</th><th>提醒</th><th>日志记录</th><th align = 'left'>描述</th></tr></thead>
					<tbody>
					<?php
					 $title = array();
					 $title_name = array();
						$sql_cat ="select webid,name,description,type from webcat where webid = -1 order by type";
						$res_cat = $db->query2($sql_cat,"M",false);
						foreach($res_cat as $row)
						{
						  $title[] = $row['type'];
						  $title_name[]= $row['name'];
					?>
					<tr>
						<td align = 'left'><a href="#" onclick="onShowHide(<?php echo "'div_web_id_".$row['type']."'"?>)"><?php echo $row['name']?></a></td>
						<td align = 'center'><?php echo $row['type']?></td>
						<td align = 'center'><input type=checkbox id = <?php echo "'webid_b_" . $row['type'] . "'"?> onclick= "on_select_web(&quot;<?php echo $row['type'] ?> &quot;,'b')" ></td>
						<td align = 'center'><input type=checkbox id = <?php echo "'webid_t_" . $row['type'] . "'"?> onclick ="on_select_web(&quot;<?php echo $row['type']?>&quot;,'t')" ></td>
						<td align = 'center'><input type=checkbox id = <?php echo "'webid_l_" . $row['type'] . "'"?> onclick= "on_select_web(&quot;<?php echo $row['type'] ?> &quot;,'l')"></td>
						<td align = 'left'><?php echo $row['description']?></td>
					</tr>
                    <?php
						}
					?>
					</tbody></table>
					<div class = "all_web">
					<?php
					   for($w_i=0;$w_i<count($title);$w_i++)
					   {
					?>
					   <div align ="center" id = <?php echo "'div_web_id_" .$title[$w_i] ."'" ?>>   
						  <table align ="center" ><tr><td><input type=checkbox id =<?php echo "'webid_all_".$title[$w_i]."'" ?> onclick="on_select_web_all(&quot;<?php echo $title[$w_i]?>&quot;)" ></td><td><b>所有<?php echo $title_name[$w_i]?>的类别</b> </td></tr></table>
					      <?php  get_catagory_data($title[$w_i],$webid_d)?> 
						  <h1></h1>
						</div>
						<script>$("#div_web_id_"+ <?php echo $title[$w_i] ?>).hide();</script>
					<?php   
					   }
					?>
					</div>
	                <script>$("#div_web_id_"+<?php echo $title[0]?>).show();</script>
					<?php
					//开始向复选框里面赋值
					function splitwebctl($webctl,&$webid_d)
					{
						$pieces = explode("|", $webctl);
						foreach($pieces  as $pi)
						{
							if($pi == "")
								continue;
							$ar = explode(",",$pi);
							//$ar[0] 子ID号
							$art = $ar[0];
					?>
                    <script>
					set_checkbox("web",<?php echo $ar[0]?>,<?php echo $ar[1]?>,<?php echo $webid_d[$ar[0]] ?>);
					</script> 					
					<?php	
						}	
					}
					?>
					<?php
					   
					   if($webctl!="")
					   {
							splitwebctl($webctl,$webid_d);	
					   }
					   
					?>
					<?php
					 /*  foreach($webid_d as $key=>$value)
					   {
					      echo "webid " . $key . " is " . $value . "<br>"; 
					   }*/
					?>
			</div>
		</div>

			<!-- FileDownLoad -->
			<div class="tool">
			<div class="details">
					<h3>您希望阻止或记录哪些类型的文件下载</h3>
					<table><tr><td width=20><input type="checkbox" <?php if($filefilter==1) echo $_use; else echo $_not_use;?> id = "file_if_use"></td><td align ='left' width =100>是否启用</td><tr><table>
					<h2></h2>
					<table cellpadding="0" cellspacing="0" border="0" class="display" id="file_table">
					<thead><tr><th align='left'>类别</th><th>代码</th><th>阻挡</th><th>日志记录</th><th align = 'left'>描述</th></tr></thead>
					<tbody>
					<?php
						 $sql_file ="select typeid,name,description from filecat order by typeid";
					 	 $res_file = $db->query2($sql_file,"M",false);
						 foreach($res_file as $row)
						 {
					?>
					<tr>
						<td align = 'left'><?php echo $row['name']?></td>
						<td align = 'center'><?php echo $row['typeid']?></td>
						<td align = 'center'><input type=checkbox  onclick = "g_file_id_value_click(<?php  echo "'". $row['typeid']."'" ?>)" id = <?php echo "'fileid_b_" . $row['typeid'] . "'"?> ></td>
						<td align = 'center'><input type=checkbox  onclick = "g_file_id_value_click(<?php echo "'". $row['typeid']."'" ?>)" id = <?php echo "'fileid_l_" . $row['typeid'] . "'"?> ></td>
						<td align = 'left'><?php echo $row['description']?></td>
					</tr>
                    <?php
						}
					?>
					</tbody>
					</table>
					<?php
					   function splitfilectl($filectl)
						{
							$pieces = explode("|", $filectl);
							foreach($pieces  as $pi)
							{
								if($pi == "")
									continue;
								$ar = explode(",",$pi);
					?>
					<script>set_checkbox("file",<?php echo $ar[0]?>, <?php echo $ar[1]?>)</script>
                    <?php					
							}	
						}
					?>
					<?php
					   if($filectl!="")
					   {
					      splitfilectl($filectl);
					   }
					?>
			</div>
            </div>
			<!-- keyword -->
			<div class="tool">
			<div class="details">
		  		 <?php
				    
				    function splitkeyword($keyword)
					{
					    $i =0;
						$pieces = explode("|", $keyword);
						foreach($pieces as $p)
						{
							if($p=="")
								continue;
							$ar = explode(",",$p);//
							$key = urldecode($ar[0]);
							$keyv = $ar[1];
							$block = "";
							$log   = "";
							switch($keyv)
							{
							case '1':
								$log = "checked='checked'";$block = "";
						     break;
							case '2':
								$log = "";$block = "checked='checked'";
                             break;
							case '3':
								$log ="checked='checked'";$block = "checked='checked'";
                             break;		
							default:
							 break;						  
							}
					?>					
					<tr>
						<td align = 'center'><?php echo $key?></td>
						<td align = 'center'><input  type=checkbox <?php echo $block?> onclick="g_keywork_id_click(<?php echo "'".$key."'"?>)" id = <?php echo "'keyword_b_" . $key  . "'"?> ></td>
						<td align = 'center'><input  type=checkbox <?php echo $log?> onclick="g_keywork_id_click(<?php echo "'".$key."'"?>)"  id = <?php echo "'keyword_l_" . $key  . "'"?> ></td>
						<td align = 'center'><a href ="#" onclick="delete_keyword(&quot;<?php echo $key?>&quot;)">删除</a></td>
					</tr>
					
					<script>add_keyword(<?php echo "'".$key . "'"?>,<?php echo $i?>,<?php echo "'".$keyv . "'" ?>)</script>					
					<?php
					     $i++;
						}//foreach end;
					}
					?>
  					
				  <!-- this is the keyword -->	
					<h3>您希望阻止或记录哪些关键词</h3>
					<table><tr><td width=20><input type="checkbox" <?php if($keywordfilter==1) echo $_use; else echo $_not_use;?> id = "keyword_if_use"></td><td align='left' width =100>是否启用</td><tr><table>
					<h2></h2>

					<table cellpadding="0" cellspacing="0" border="0" class="display" id="keyword_table">
					<thead><tr><th align="center">关键词</th><th align="center">阻挡</th><th align="center">日志记录</th><th align="center">删除</th></tr></thead>
					<tbody>
					<?php
					
					if($keyword !="")
					   splitkeyword($keyword);
					?>
                    </tbody></table>
					<h3>增加关键词</h3>
					
					<br>
					<table><tr><td>输入:</td><td><input type="edit" width = "300" id = "keyword_input" /></td><td><a href="javascript:void(0);" onclick="fnClickAddRow();">增加</a></td>
					</tr></table>
				 </div>
			</div>
			<!-- AUDIT -->
			<div class="tool">
			<div class="details">
					<h3>请在需要审计的项目前打钩：</h3>	
					<?php
					  //$SQL = "select smtpaudit,pop3audit,postaudit from policy where policyid=" . 
					  $smtp ="";$pop3="";$post="";
					  if($smtpaudit!=0)
					     $smtp  = "checked='checked'";
                      if($pop3audit!=0)
                         $pop3 = "checked='checked'";
                      if($postaudit!=0)
                         $post = "checked='checked'"; 						 
					?>
					<p>
					<table>
					<tr>
					<td><input type="checkbox" id="audit_smtp" value="1" <?php echo $smtp;?>> </td><td align="left">客户端发送邮件</td>
					</tr><tr>
					<td><input type="checkbox" id="audit_pop3" value="2" <?php echo $pop3;?>> </td><td align="left">客户端接收邮件</td>
					</tr><tr>
					<td><input type="checkbox" id="audit_post" value="3" <?php echo $post;?>> </td><td align="left">网页发送邮件及发帖审计</td></tr>
                    </table>					
					</p>
			</div>
			
			</div>



			 
		</div><!-- div tools-->
			<!-- intro "page" -->
	<div id="intro" class="tool">
 
		<img style="margin:-17px 0 28px 0" width="721" height="346" alt="定制策略"
			src="./images/tools.jpg" />
 
		<div class="details">
		<p align="left"><strong>定制您需要的策略</strong>您可以选取网站分类,网络软件等进行提醒和封堵，也可以设定禁止的关键词和不能下载的文件,进行审计行为记录等等</p>
		<p align="left">当确定完毕，可以到这里来保存策略:<input type =button class="inputButton_se" value = '保存' align ='left' onclick = "savepolicy(<?php echo $policyid?>)">
		</p>
			
		</div>

 
	</div>
 
	<!-- required for IE6/IE7 -->
	<br clear="all" />
 
	<!-- thumbnails -->
	<div id="thumbs" class="t">
 
		<!-- intro page navi button -->
		<a id="t0" class="active"></a>
 
		<!-- scrollable navigator root element -->
		<div class="navi">
			<a style="display:none"></a>
			<a id="t1"></a>
			<a id="t2"></a>
			<a id="t3"></a>
			<a id="t4"></a>
			<a id="t5"></a>
			<a id="t6"></a>
		</div>
 
	</div>
</div><!--div scroll -->


   
<script> 
// initialize scrollable and return the programming API
var api = $("#scroll").scrollable({
	items: '#tools'
 
// use the navigator plugin
}).navigator().data("scrollable");
 
 
// this callback does the special handling of our "intro page"
api.onBeforeSeek(function(e, i) {
 
	// when on the first item: hide the intro
	if (i) {
		$("#intro").fadeOut("slow");
 
		// dirty hack for IE7-. cannot explain
		if ($.browser.msie && $.browser.version < 8) {
			$("#intro").hide();
		}
 
	// otherwise show the intro
	} else {
		$("#intro").fadeIn(1000);
	}
 
	// toggle activity for the intro thumbnail
	$("#t0").toggleClass("active", i == 0);
});
 
// a dedicated click event for the intro thumbnail
$("#t0").click(function() {
 
	// seek to the beginning (the hidden first item)
	$("#scroll").scrollable().begin();
 
});
 
</script>

	
</body>
</html>
