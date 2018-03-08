<?php

   require_once('_inc.php');
   
   $ftp = $_REQUEST['ftp']; 
   $tftp = $_REQUEST['tftp']; 
   $ids = $_REQUEST['id']; 
	if(isset($ftp))
	{
	   $ftpcheck=explode(',',$ftp);
	   $tftpcheck=explode(',',$tftp);
	   $id=explode(',',$ids);
	   $i=0;
	   foreach($ftpcheck as $varftp)
	 
		  {
		   if($varftp == "")
           break;		   
	       $SQL = "update file_pass set ftp=$varftp,tftp=$tftpcheck[$i] where id=$id[$i]";
		  // echo $SQL."<br>";
		   $db->query2($SQL);
		   
           $i++;   
		  }
      	
	  
	};
 
?>