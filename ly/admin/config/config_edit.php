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
    $oper = $_POST['oper'];
  	 $ip = $_POST['ip'];
  	 $name = $_POST['name'];       
	 $netmask = $_POST['netmask'];
	  
  
  	 
  
     
	if ($oper == "edit") 
    { 
    	$commod = "echo 'ifconfig $name  $ip  netmask $netmask'  > /mnt/test";
  	   exec($commod);
     
     } 
	 
	echo json_encode(array('succees'=>true,'message'=>"成功"));

?> 




