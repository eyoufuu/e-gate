<!DOCTYPE html>
<?php
   require_once('_inc.php');
?>

<?php
    session_start();
   
   if(isset($_POST['lock']))
   { 
    $_SESSION["date1"] = $_POST['date1'];
    $_SESSION["date2"] = $_POST['date2'];
    $_SESSION["symbol"]= $_POST['lock'];   
   

    switch($_POST['lock']) 
    {
    
    	case "1":
    		break;
		case "2":
			$_SESSION["username"] =$_POST['username'];
    		break;
    	case "3":
    		$_SESSION["account"] = $_POST['account'];
    		break;
    	case "4":
    		$_SESSION["ips"] = $_POST['ips'];
      	$_SESSION["ipe"] = $_POST['ipe'];
    		break;
    	default:
    		break;    
   }
  }         
?>
<html>
	<head>
	<title>报表条件</title>    
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
<script language="javascript" type="text/javascript" src="./My97DatePicker/WdatePicker.js" defer="defer"></script>
<script language="javascript" type="text/javascript" src="./ipcheck.js"></script>          

                 
    <script  language="JavaScript" >
   function submit_check()
		{
		  if(checkinput())
			{	
			  return true;
			}
			else
			{
			  return false;
			}			
		};		  
    
   
     $(function()
	{
 		$("#all").click(function(){ 
        	$("#user").attr("disabled","disabled");
        	$("#acc").attr("disabled","disabled");
        	$("#ips").attr("disabled","disabled");
        	$("#ipe").attr("disabled","disabled");})
        
        $("#userid").click(function(){ 
        	$("#user").attr("disabled","");
            $("#acc").attr("disabled","disabled");
            $("#ips").attr("disabled","disabled");})
            
        $("#accid").click(function(){ 
            $("#user").attr("disabled","disabled");
            $("#acc").attr("disabled","");
            $("#ips").attr("disabled","disabled");
            $("#ipe").attr("disabled","disabled");})
            
        $("#ipid").click(function(){ 
            $("#user").attr("disabled","disabled");
            $("#acc").attr("disabled","disabled");
            $("#ips").attr("disabled","");
            $("#ipe").attr("disabled","");})
                  
        $("#submit").click(function(){ 
            if($("#userid").attr("checked") == true)
			{
			   if ($("#user").val()=="")
					alert("请输入用户名");
			}
               
            if($("#accid").attr("checked") == true)
			{
			   if ($("#acc").val()=="")
					alert("请输入帐号");
			}
            if($("#ipid").attr("checked") == true)
			{
			    submit_check()
			}}) 

         $("#tab_updown").click(function(){ 
        	 
             $("#call").attr("src","updownflow.php");})
             
         $("#tab_pro").click(function(){ 
        	 
        	     $("#call").attr("src","proflow.php");})
              
         $("#tab_ip").click(function(){ 
           
           
             $("#call").attr("src","topipflow.php");})
         $("#tab_blockpro").click(function(){ 
           	
         
             $("#call").attr("src","topblockpro.php");})
         $("#tab_blockip").click(function(){ 
           	
            
             $("#call").attr("src","topblockip.php");})
               
         
         $("#tab_web").click(function(){
           
             
                 $("#call").attr("src","webcount.php");})  
                    
         $("#tab_webcat").click(function(){ 
             
               
                 $("#call").attr("src","topwebcat.php");}) 
                  
                               
               
                    
      });
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
</head>
<style>.a3{width:30;border:0;text-align:center}</style>  
<body> 
   <strong>报表</strong>---> <strong>统计</strong>       
	<hr style="BORDER-BOTTOM-STYLE: dotted; BORDER-LEFT-STYLE: dotted; BORDER-RIGHT-STYLE: dotted; BORDER-TOP-STYLE: dotted" color=#7FAAFF >

	<br>
	<form name=queryinput action="condition2.php" method="post" >
		<div>
		  开始日期：<input name="date1" class="Wdate" type="text"  onfocus="new WdatePicker(this,null,false,'whyGreen')" value= "<?php  if(isset($_SESSION["date1"])){echo $_SESSION["date1"]; } else{ date_default_timezone_set('Asia/Shanghai');echo date('Y-m-d');} ?>" />
		  结束日期：<input name="date2" class="Wdate" type="text"  onfocus="new WdatePicker(this,null,false,'whyGreen')" value="<?php   if(isset($_SESSION["date2"])){echo $_SESSION["date2"]; } else{ date_default_timezone_set('Asia/Shanghai');echo date('Y-m-d');} ?>" />	 
		</div>
		<div>
		<table border="0" cellspacing="0" cellpadding="2">
			<tr><td><input type="radio" name="lock" id="all"  value="1" <?php if($_SESSION["symbol"]==1 || $_SESSION["symbol"]=='') echo 'checked'; ?>> 全部用户 </td></tr>
		    <tr>
		    	<td><input type="radio" name="lock" id="userid"  value="2" <?php if($_SESSION["symbol"]==2 ) {echo 'checked';}  ?> > 用户名： </td>
		    	<td><input name="username" type="text" id="user" <?php if($_SESSION["symbol"]!=2 ) {echo 'disabled';}  ?> value="<?php  if(isset($_SESSION["username"])) {echo $_SESSION["username"]; }  ?>" ></td>
		    </tr>
		  	<tr>
		    	<td><input type="radio" name="lock" id="accid"  value="3"  <?php if($_SESSION["symbol"]==3 ) {echo 'checked';} ?> > 帐号： </td>
		    	<td><input name="account" type="text" id="acc" <?php  if($_SESSION["symbol"]!=3 ) {echo 'disabled';} ?> value="<?php if(isset($_SESSION["account"])) {echo $_SESSION["account"]; } ?>" ></td>
		  	</tr>
			<tr>
		    	<td><input type="radio" name="lock" id="ipid" value="4" <?php if($_SESSION["symbol"]==4 ) {echo 'checked';} ?> > 地址范围：</td>
				<td>
		    		<input id="ips" name="ips" type="text" id="ips" <?php if( $_SESSION["symbol"]!=4 ) {echo 'disabled';} ?> value="<?php  if(isset($_SESSION["ips"])) {echo $_SESSION["ips"]; } ?>" >-
		     		<input id="ipe" name="ipe" type="text" id="ipe" <?php if( $_SESSION["symbol"]!=4 ) {echo 'disabled';} ?> value="<?php if(isset($_SESSION["ipe"])) {echo $_SESSION["ipe"]; } ?>"  >
		    	</td>
		  </tr>
		</table> 
		</div>
		<INPUT type="submit" name="提交" value="提交" id="submit" size="20" >	
	</form>
  


		<div id="tabs">
			<ul>
				<li><a href="#tabs-1" id="tab_updown" >上下行流量</a></li>
				<li><a href="#tabs-1" id="tab_ip" >Top10IP流量</a></li>
				<li><a href="#tabs-1" id="tab_blockip" >Top10阻挡IP</a></li>
				<li><a href="#tabs-1" id="tab_pro" >Top10协议流量</a></li>
				<li><a href="#tabs-1" id="tab_blockpro" >Top10阻挡协议</a></li>
				<li><a href="#tabs-1" id="tab_web" >Top10网站</a></li>
				<li><a href="#tabs-1" id="tab_webcat" >Top10网站分类</a></li>
			</ul>	
		
  
		
	
	<div id="tabs-1" class="clear"  scrolling=auto>
	<Iframe id="call" name="call" width="100%"  onload="this.height=call.document.body.scrollHeight"  frameborder="0" scrolling="auto" ></Iframe>
	</div>


</body>
</html>
