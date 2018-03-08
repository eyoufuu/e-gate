<?php
   require_once('_inc.php');
   
?>



<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>test page</title>	
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
 
  
   <br>
	
        <div class="common" style="width: 800px"> 
        <div class="common_title"> 
        <div class="common_title_left"></div> 
        <div class="common_title_word">上下行流量</div> 
        <div class="common_title_right"></div> 
        </div> 
	</div>		
	<br>
    
        <br>
<!--以下表格要用php生成就可以了-->

<?php
 
      $arr = "call updownflow(20100304,0,0,0,0)";
     $arr = $db->fetchRows($arr);


?> 

<table>
	<caption></caption>
	<thead>
            
		<tr>
			<td></td>
              <?php  foreach($arr as $value) {        ?>   
			<th><?php echo $value['logtime'];  ?></th>
		  <?php  }    ?>			
		</tr>
	</thead>
	<tbody>
          
		<tr>
                       <th>上行流量</th>
		<?php  foreach($arr as $value) {        ?>   	
                     <td><?php echo $value['upflow'];  ?></td>
			
		     <?php  }    ?>	
                  
                 </tr>
                 <tr>
                       <th>下行流量</th>
		<?php  foreach($arr as $value) {        ?>   	
                     <td> <?php echo $value['downflow'];  ?></td>
			
		     <?php  }  unset($arr); ?>	
                  
                 </tr>
               
	  
	</tbody>
</table>


<br>
<br>
 

<div align="left">
		<script language="JavaScript">
			get_graph();
		</script>
</div>


</body>
</html>