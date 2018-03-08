 <?php
  require_once('_inc.php');
?>



<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>procount</title>	
		<link type="text/css" rel="stylesheet" href="./chart/visualize.jQuery.css"/>
		<link type="text/css" rel="stylesheet" href="./chart/demopage.css"/>
		<script type="text/javascript" src="./chart/jquery.min.js"></script>
		<script type="text/javascript" src="./chart/time.js"></script>
		
		<!--[if IE]><script type="text/javascript" src="./chart/excanvas.compiled.js"></script><![endif]-->
		<script type="text/javascript" src="./chart/visualize.jQuery.js"></script>
		<script type="text/javascript">
			function get_graph(){
				//make some charts
				$('table').visualize({type: 'pie', pieMargin: 10, title: ''});
				$('table').visualize({type: 'line'});
				$('table').visualize({type: 'area'});
				$('table').visualize();
			};

                        
                         function get_pie() {

                             $('table').visualize({type: 'pie', pieMargin: 10, title: ''});         
                         }         
                         
		</script>
                 <link href="../common/main.css" rel="stylesheet" type="text/css"/>
                 <script type="text/javascript" src="../common/common.js"></script>

	</head>
	<body>	
 <?php
    session_start();
   $date1 = $_SESSION["date1"];
   list($year,$month,$day)=explode("-",$date1);
   $datet1 = $year.$month.$day; 
   
   $date2 = $_SESSION["date2"];
   list($year,$month,$day)=explode("-",$date2);
   $datet2 = $year.$month.$day; 
   $ips   = $_SESSION["ips"] ;
   $ipsi = ip2long($ips);
   $ipe   = $_SESSION["ipe"];
   $ipei = ip2long($ipe);
   $symbol= $_SESSION["symbol"]; 
   $username =  $_SESSION["username"];
   $account =  $_SESSION["account"] ;
?>
  
<br>
	
        <div class="common" style="width: 800px"> 
          <div class="common_title"> 
             <div class="common_title_left"></div> 
             <div class="common_title_word">top10阻挡协议</div> 
             <div class="common_title_right"></div> 
          </div> 
	</div>		
<br>
<br>
<!--以下表格要用php生成就可以了-->

<?php

 if ($symbol == "2")
  {
     $arr = "select id from useraccount where name= '".$username."'";
     $arr = $db->fetchRow($arr);
     $accountid= $arr["id"];
   }
 elseif ($symbol =="3")
   {
       $arr = "select id from useraccount where account= '".$account."'";
       $arr = $db->fetchRow($arr);
       $accountid= $arr["id"];
   };




if( $symbol == "1")
   {
     
     $arr = "call top10blockpro($datet1,$datet2,0,0,0,0)";
   }
elseif ($symbol == "2" || $symbol == "3")
    {
      $arr = "call top10blocpro($datet1,$datet2,1,$accountid,0,0)";
    }
elseif ($symbol == "4")
    { 
      $arr = "call top10blockpro($datet1,$datet2,2,0,$ipsi,$ipei)";
     };

$arr = $db->fetchRows($arr);

?> 

<table>
	<caption></caption>
	<thead>
		<tr>
			<td>协议名称</td>
			<th></th>
			
		</tr>
	</thead>
	<tbody>
           <?php
                foreach($arr as $value) {        ?>     
		<tr>
			<th><?php echo $value['name'];  ?></th>
			<td><?php echo $value['sumblock'];  ?></td>
			
		</tr>
	    <?php	
              }
            
              unset($arr);
        
	   ?>	
	</tbody>
</table>

<?php

$db->close();
$db->_open();
?>


<br>
<br>

       <div class="common" style="width: 800px"> 
       <div class="common_title"> 
       <div class="common_title_left"></div> 
       <div class="common_title_word">top10放行协议</div> 
       <div class="common_title_right"></div> 
        </div> 
	</div>		
	<br>
	<br>
<!--以下表格要用php生成就可以了-->
<?php

if( $symbol == "1")
   {
    
    $arr = "call top10passpro($datet1,$datet2,0,0,0,0)";
   }

elseif ($symbol == "2" || $symbol == "3")
  {
    $arr = "call top10passpro($datet1,$datet2,1,$accountid,0,0)";
  }
elseif ($symbol == "4")
   { 
    $arr = "call top10passpro($datet1,$datet2,2,0,$ipsi,$ipei)";
   };


$arr = $db->fetchRows($arr);


?> 

<table>
	<caption></caption>
	<thead>
		<tr>
			<td>协议名称</td>
			<th></th>
			
		</tr>
	</thead>
	<tbody>
           <?php
                foreach($arr as $value) {        ?>     
		<tr>
			<th><?php echo $value['name'];  ?></th>
			<td><?php echo $value['sumpass'];  ?></td>
			
		</tr>
	    <?php	
              }
            
              unset($arr);
        
	   ?>	
	</tbody>
</table>

<?php

$db->close();

?>

<div align="left">
		<script type="text/javascript" >
			get_pie();
		</script>
</div>

<br>
<br>
	
</body>
</html>