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

                         function get_line(){
                                  $('table').visualize({type: 'line'});


                         };
                         function get_pie() {

                             $('table').visualize({type: 'pie', pieMargin: 10, title: ''});         
                         }         
                         function get_pie1() {

                             $('table').visualize({type: 'pie', pieMargin: 10, title: ''});         
                         }         
		</script>
                 <link href="../common/main.css" rel="stylesheet" type="text/css"/><script type="text/javascript" src="../common/common.js"></script>

	</head>
	
   <body>	
 <?php
    echo  $_SESSION["aa"];
  ?>
 
   </body>

</html>