<?php

   require_once('_inc.php');
   
    $t = $_REQUEST['vv']; 
  
	if(isset($t))
	{
	   $keycheck=explode(',',$t);
	   //$i=0;
	   foreach($keycheck as $var)
	   {
	    if(($var=="0") || ($var=="1"))    
	      {
		    if($var=="0")
			$block=0;
			else
			$block=1;
		    $SQL = "update file_transter set block=$block where `address`='$key'";
			$SQL2 = "update specweb set pass=$var where `host`='$key'";
			
		   //echo $SQL;
		   $db->query2($SQL);
           $db->query2($SQL2);		   
			
			continue;
		  }
        
		$key=$var;		  
		  
	   }
	
	  
	};
	
 
?>