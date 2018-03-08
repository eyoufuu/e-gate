<?php
   require_once('_inc.php');
?>

<?php
function getcolor()
{
	static $colorvalue;
	if($colorvalue=="'bgFleet'")
		$colorvalue="";
	else
		$colorvalue="bgFleet";
	return($colorvalue);
}
?>

<html>
	<head>
           <title>报表条件</title>    
           <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
           <link href="../common/main.css" rel="stylesheet" type="text/css"/>
           <link href="./css/tab.css" rel="stylesheet" type="text/css"/>
           <script language="javascript" type="text/javascript" src="./My97DatePicker/WdatePicker.js" defer="defer"></script>
           <script language="javascript" type="text/javascript" src="./jquery-1.3.2.js"></script>
           <script type="text/javascript" src="../common/common.js"></script>
           <script  language="JavaScript" >
             $(function() {
              
                 $("#tab_admin").click(function(){ 
                 $("#tab_audit").attr("class","goodsDetailTab");
                 $("#tab_admin").attr("class","goodsDetailTab active");
                 $("#tab_oper").attr("class","goodsDetailTab");
                 $("#call").attr("src","admin.php");
                   
                   })  
                
                 $("#tab_audit").click(function(){ 
                 $("#tab_admin").attr("class","goodsDetailTab");
                 $("#tab_audit").attr("class","goodsDetailTab active");
                 $("#tab_oper").attr("class","goodsDetailTab");
                 $("#call").attr("src","audit.php");
                   
                   })  
                
                 $("#tab_oper").click(function(){ 
                 $("#tab_admin").attr("class","goodsDetailTab");
                 $("#tab_oper").attr("class","goodsDetailTab active");
                 $("#tab_audit").attr("class","goodsDetailTab");
                 $("#call").attr("src","oper.php");
                 
               })  

               $("#submit").click(function(){ 
                 
                 
                 
                 if( $("#limit").val() < $("#3").val())
                 {
                   alert("开始日期超出日期界限");
                 }
              else if ( $("#3").val()>$("#4").val())
                 { 
                  alert("开始日期超出结束日期"); 
                 }   
               else if ( $("#4").val()>$("#limit").val())
                 { 
                  alert("结束日期超出日期界限"); 
                 }   ;                      
                }) 
                
                  
              });
          </script>


           <?php
                
                  $nowdate=date("Y-m-d");
                
                 if ($_POST['date3']!="") 
                {
                  $date3 = $_POST["date3"]; 
                  $date4 = $_POST["date4"]; 
                }
                 else
               {
                  $date3=date("Y-m-d");
                  $date4=date("Y-m-d");
                }
             ?>
	
    </head> <style>.a3{width:30;border:0;text-align:center}</style>  
 <body>  
               
               
                 <INPUT type="hidden" name="limit" id="limit" value="<?php echo $nowdate; ?>">
		<div class="bodyTitle">
			<div class="bodyTitleLeft"></div>
			<div class="bodyTitleText">请输入查询条件</div>
                        
		</div>
		 <br>
               
 <form name=queryinput action="syscondition.php" method="post" >
			
 <div> 
开始日期：<input name="date3" class="Wdate"  id="3"  type="text"  onfocus="new WdatePicker(this,null,false,'whyGreen')" value="<?php echo $date3; ?> " />
结束日期：<input name="date4" class="Wdate" id="4" type="text" onfocus="new WdatePicker(this,null,false,'whyGreen')" value="<?php echo $date4; ?>" /> 
</div>
 <br>
 	
<INPUT type="submit" name="提交" value="提交" id="submit" size="20">	
			
</form>
  
  <div class="goods-detail-tab clearfix" id="tabs">
  <div class="goodsDetailTab" id="tab_admin" >
     <span>管理员</span>
    </div>
  <div class="goodsDetailTab" id="tab_audit">
     <span>审计员</span>
    </div>
  <div class="goodsDetailTab" id="tab_oper" >
     <span>操作员</span>
    </div>

  </div>
<div class="clear">

<Iframe id=call name=right scrolling=auto frameborder=0  height="1000" width="1000"></Iframe>

</div>


 <?php
       
       session_start();
      $_SESSION["date3"] = $_POST['date3'];
      $_SESSION["date4"] = $_POST['date4'];
            
    ?>         

		
	</body>
</html>