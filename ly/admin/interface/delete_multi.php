<?php

  /*
   * File: delete.php
   * 
   * Modified: 2010-3-2
   * By: qianbo (qianbo@chd.edu.cn)
   *
   * Created: 2010-3-1
   * By: qianbo (qianbo@chd.edu.cn)
   *
   * Link: http://www.lysafe365.com/
   */
   
   require_once("_inc.php");
   include("../dbconfig.php");
   
   	$db_1 = mysql_connect($dbhost, $dbuser, $dbpassword)
	or die("连接错误: " . mysql_error());
     

	mysql_select_db($database) or die("Error conecting to db.");
   
   
   
   $in = $_POST['ip_dels'];
   $pieces = explode(",", $in);
   $in_ip ="";
   foreach($pieces as $value)
      $in_ip = "'"+$value+"'";
   //$in="'0'";
   $SQL = "DELETE FROM ipmac WHERE ip in ($in_ip)"; 
   echo $SQL . "<br>";
   $result = mysql_query($SQL);
   mysql_close($db_1);
   //header("location: ip_mac.php");
?>