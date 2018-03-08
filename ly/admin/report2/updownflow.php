
<?php
  
  require_once('_inc.php');
  session_start();
   if(!isset($_SESSION["date1"]))
      { 
   		header("Location:../report2/condition.php"); 
		return;
	  }	
   $ds = $_SESSION["date1"];
   $de = $_SESSION["date2"];
   if($ds==$de)
     $timeshow="小时";
   else
     $timeshow="日";
   $symbol= $_SESSION["symbol"]; 
   list($year,$month,$day)=explode('-',$ds);
   $date_ym = $year.$month;
   $tablename=$date_ym."flowdata";
   $dates = strtotime($ds);
   $datee = strtotime($de);
   $datee=$datee+86400; 
   $arr = "select systemmode from globalpara";
   $arr = $db->fetchRow($arr);
   $systemmode= $arr["systemmode"];
   switch($symbol)
    {
    	case "1":
		    if($dates == $datee-86400)
              {              
			  
	           $arr = "select time(FROM_UNIXTIME(logtime)) as logtime,sum(upflow)/1048576 as upflow,sum(downflow)/1048576 as downflow, sum(upflow+downflow)/1048576 as updownflow ,hour(FROM_UNIXTIME(logtime)) as hours from $tablename where logtime between $dates and $datee group by hours ";
              }	    
			else 
			  {
			   $arr = "select date(FROM_UNIXTIME(logtime)) as logtime,sum(upflow)/1048576 as upflow,sum(downflow)/1048576 as downflow,sum(upflow+downflow)/1048576 as updownflow,day(FROM_UNIXTIME(logtime)) as days from  $tablename where logtime between $dates and $datee group by days ";
    		  }
			
         $show="全部"; 
    		break;
    	case "2":
    	   
    		$username =  $_SESSION["username"];
    		$show=$username;
    		 if ($systemmode == 0)
            {
              $arr = "select bindip from useraccount where name= '$username'";
		        $arr = $db->fetchRow($arr);
		        $accountid= $arr["bindip"];
                if($dates == $datee) 
                  {
				    $arr = "select time(FROM_UNIXTIME(logtime)) as logtime,sum(upflow)/1048576 as upflow,sum(downflow)/1048576 as downflow, sum(upflow+downflow)/1048576 as updownflow ,hour(FROM_UNIXTIME(logtime)) as hours from $tablename where (ip_inner=$accountid) and (logtime between  $dates and $datee) group by hours ";
				  }	
                else
                   {
				   $arr = "select date(FROM_UNIXTIME(logtime)) as logtime,sum(upflow)/1048576 as upflow,sum(downflow)/1048576 as downflow,sum(upflow+downflow)/1048576 as updownflow,day(FROM_UNIXTIME(logtime)) as days from  $tablename where (ip_inner=$accountid) and (logtime between $dates and $datee) group by days ";
				   }				
	        } 
           else
            {
                $arr = "select id from useraccount where name= '$username'";
		        $arr = $db->fetchRow($arr);
		        $accountid= $arr["id"];
		      if($dates == $datee) 
                  {
				   
				    $arr = "select time(FROM_UNIXTIME(logtime)) as logtime,sum(upflow)/1048576 as upflow,sum(downflow)/1048576 as downflow, sum(upflow+downflow)/1048576 as updownflow,hour(FROM_UNIXTIME(logtime)) as hours from $tablename where (ip_inner=$accountid) and (logtime between  $dates and $datee) group by hours ";
				  }	
                else
                   {
				   $arr = "select date(FROM_UNIXTIME(logtime)) as logtime,sum(upflow)/1048576 as upflow,sum(downflow)/1048576 as downflow,sum(upflow+downflow)/1048576 as updownflow,day(FROM_UNIXTIME(logtime)) as days from  $tablename where (ip_inner=$accountid) and (logtime between $dates and $datee) group by days ";
				   }	  
				
	        }    
      		break;
     	case "3":
    		   $account = $_SESSION["account"];
    		   $show=$account;
    		   $arr = "select id from useraccount where account= '$account'";
		       $arr = $db->fetchRow($arr);
		       $accountid= $arr["id"];
		      if($dates == $datee) 
                  {
				   
				    $arr = "select time(FROM_UNIXTIME(logtime)) as logtime,sum(upflow)/1048576 as upflow,sum(downflow)/1048576 as downflow, sum(upflow+downflow)/1048576 as updownflow ,hour(FROM_UNIXTIME(logtime)) as hours from $tablename where (account_id=$accountid) and (logtime between  $dates and $datee) group by hours ";
				  }	
                else
                   {
				   $arr = "select date(FROM_UNIXTIME(logtime)) as logtime,sum(upflow)/1048576 as upflow,sum(downflow)/1048576 as downflow,sum(upflow+downflow)/1048576 as updownflow,day(FROM_UNIXTIME(logtime)) as days from  $tablename where (account_id=$accountid) and (logtime between $dates and $datee) group by days ";
				   }	  
    		break;
    	case "4":
			  
   			  $ipsi = ip2long($_SESSION["ips"]);
			  $ipei = ip2long($_SESSION["ipe"]);
			   if($ipsi==$ipei)
			     $show=$_SESSION["ips"];
				else
		  	     $show=$_SESSION["ips"]."--".$_SESSION["ipe"];
			   if($dates == $datee) 
                  {
				   
     			    $format = "select time(FROM_UNIXTIME(logtime)) as logtime,sum(upflow)/1048576 as upflow,sum(downflow)/1048576 as downflow, sum(upflow+downflow)/1048576 as updownflow ,hour(FROM_UNIXTIME(logtime)) as hours from  %s where (ip_inner between %u and %u) and (logtime between  %u and %u) group by hours ";
        	      }	
                else
                   {
				    $format = "select date(FROM_UNIXTIME(logtime)) as logtime,sum(upflow)/1048576 as upflow,sum(downflow)/1048576 as downflow,sum(upflow+downflow)/1048576 as updownflow,day(FROM_UNIXTIME(logtime)) as days from  %s where (ip_inner between  %u and %u) and (logtime between %u and %u) group by days ";
	               }	  
				   
				   $arr = sprintf($format,$tablename,$ipsi,$ipei,$dates,$datee);
	 		    
        	break;
          	 default:
    	   return;
    }
	//echo $arr;
   $arr = $db->fetchRows($arr);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>上下行流量</title>
		
		
		<!-- 1. Add these JavaScript inclusions in the head of your page -->
		<script type="text/javascript" src="./js/jquery.min.js"></script>
		<script type="text/javascript" src="./js/highcharts.js"></script>
		<style type="text/css" title="currentStyle">
			@import "./css/demo_page.css";
			@import "./css/demo_table.css";
		</style>
		<script type="text/javascript" language="javascript" src="./js/jquery.js"></script>
		<script type="text/javascript" language="javascript" src="./js/jquery.dataTables.js"></script>
		<!--[if IE]>
		<script type="text/javascript" src="./js/excanvas.compiled.js"></script>
		<![endif]-->
		<!-- 2. Add the JavaScript to initialize the chart on document ready -->
		<script type="text/javascript">
		var chart;
		$(document).ready(function() {
			chart = new Highcharts.Chart({
				chart: {
					renderTo: 'containers',
					defaultSeriesType: 'line',
					margin: [50, 150, 60, 80]
				},
				title: {
					text: '',
					style: {
						margin: '10px 100px 0 0' // center it
					}
				},
				subtitle: {
					text: '',
					style: {
						margin: '0 100px 0 0' // center it
					}
				},
				xAxis: {
					//categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 
					//	'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
					categories: <?php
					       $i=0;
					      foreach($arr as $value){
                          $return_array[$i]=$value['logtime'];
						  
				           $i++;                         
						   }					  
				         $sdata = json_encode($return_array);
	                     echo $sdata;                 
				     ?>	,
					labels: {
						rotation: -45,
						align: 'right',
						style: {
							      font: 'normal 13px Verdana, sans-serif'
						        } 
							 },	
					title: {
						text: 'Month'
					}
				},
				yAxis: {
					title: {
						text: '流量 (MB)'
					},
					plotLines: [{
						value: 0,
						width: 1,
						color: '#808080'
					}]
				},
				tooltip: {
					formatter: function() {
			                return '<b>'+ this.series.name +'</b><br/>'+
							this.x +': '+ this.y +'MB';
					}
				},
				legend: {
					layout: 'vertical',
					style: {
						left: 'auto',
						bottom: 'auto',
						right: '10px',
						top: '100px'
					}
				},
				series: [{
					name: '上行流量',
					//data: [7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6]
					data: <?php
					          $i=0;
					      foreach($arr as $value){
                          $return_array[$i]=(float)$value['upflow'];
						  
				           $i++;                         
						   }					  
				         $sdata = json_encode($return_array);
	                     echo $sdata;       
					
					
					?>
				}, {
					name: '下行流量',
					//data: [-0.2, 0.8, 5.7, 11.3, 17.0, 22.0, 24.8, 24.1, 20.1, 14.1, 8.6, 2.5]
					 data: <?php
					           $i=0;
					           foreach($arr as $value){
                               $return_array[$i]=(float)$value['downflow'];
						       $i++;                         
						    }					  
				          $sdata = json_encode($return_array);
	                      echo $sdata;   
					   ?>
				}, {
					name: '总流量',
					//data: [-0.9, 0.6, 3.5, 8.4, 13.5, 17.0, 18.6, 17.9, 14.3, 9.0, 3.9, 1.0]
					 data: <?php
					           $i=0;
					           foreach($arr as $value){
                               $return_array[$i]=(float)$value['updownflow'];
						       $i++;                         
						    }					  
				            $sdata = json_encode($return_array);
	                      echo $sdata;   
					   ?>
				}]
			});
			
			
		});
		</script>
		
	</head>
	<body id="dt_example">
	
	<div id="container">
	        <div class="full_width big">
			   <i>上下行流量报表</i> 
			</div>
	        
			<h1>选择的时间范围：<?php list($year,$month,$day)=explode('-',$ds);$tmp=mktime(0,0,0,$month+1,0,$year);echo $ds.'至';if($datee>$tmp){echo date("Y-m-d",$tmp);}else{echo $de;} ?> &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; 选择的用户：<?php echo $show ?>  </h1>
		<!-- 3. Add the container -->
		<div id="containers" style="width: 800px; height: 400px; margin: 0 auto"></div>
	
		
		<div id="footer" style="text-align:center;">
		  <span style="font-size:10px;">Copyright &copy;2010 Powered by <a href="http://www.lysafe365.com/">凌屹信息科技</a> <?php echo date('Y/m/d') ?></span>
		</div>
		</div>
		
	</body>
</html>
