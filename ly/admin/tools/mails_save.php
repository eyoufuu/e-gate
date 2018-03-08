<?php

   require_once('_inc.php');
   
   $mails = $_REQUEST['mails']; 
   $ids = $_REQUEST['mailid'];
     
   
	if(isset($mails))
	{
	   $mailscheck=explode(',',$mails);
	   $ids=explode(',',$ids);
	   $i=0;
	   foreach($mailscheck as $var)
	     {
		   if($var == "")
            break;		   
	       $SQL = "update mail_spec set block=$var  where id=$ids[$i]";
		   echo $SQL."<br>";
		   $db->query2($SQL);
		   $i++;   
		  }
     };
 
?>