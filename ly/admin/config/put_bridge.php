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
   include("../dbconfig.php");
?>


<?php
   $test = $_GET['data'];
   echo $test;
   echo "<br>";
   $db_tc = mysql_connect($dbhost, $dbuser, $dbpassword)
   or die("不能连接: " . mysql_error());
   mysql_select_db($database) or die("选择数据库错误.");

   $SQL = "insert into global(br0) values ('$test')";
   echo $SQL;
   $result = mysql_query( $SQL ) or die("数据库查询错误.".mysql_error());
   
?>