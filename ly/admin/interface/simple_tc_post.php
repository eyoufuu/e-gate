<?php
   error_reporting(E_ALL);

   require_once('_inc.php');
   
   
   $list_ips = $_GET[ips];
   $list_ipe = $_GET[ipe];
   
   
   $ips = bindec(decbin(ip2long($list_ips)));
   $ipe = bindec(decbin(ip2long($list_ipe)));

   echo $ips;
   echo "<br>";
   echo $ipe;
   echo "<br>";

   
   
   
   //list($ip1, $ip2, $ip3, $ip4) = split('[.]',$list_ips);
   //list($ip5, $ip6, $ip7, $ip8) = split('[.]',$list_ipe);
   
    
?>