<?php

   require_once('_inc.php');
   
   $filetype = $_REQUEST['filetype']; 
   $filetypeid = $_REQUEST['filetypeid']; 
   $isfileout = $_REQUEST['isfileout']; 
   $SQL2 = "update globalpara set isfileout=$isfileout";
   $db->query2($SQL2);
	if(isset($filetype))
	{
	    $SQL = "update file_pass set filetype='$filetype' where id=$filetypeid";
		$db->query2($SQL);
       	
	};
	
 
?>