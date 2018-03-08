<?php

   require_once('_inc.php');
   
   $filetype = $_REQUEST['filetype']; 
   $ids = $_REQUEST['filetypeid'];
   $isfileout = $_REQUEST['isfileout'];
   $sym = $_REQUEST['sym'];  
   
	if(isset($sym))
	{
	   $filetypecheck=explode(',',$filetype);
	   $id=explode(',',$ids);
	   $i=0;
	   foreach($filetypecheck as $var)
	     {
		   if($var == "")
            break;		   
	       $SQL = "update file_transter set block=$var  where id=$id[$i]";
		   //echo $SQL."<br>";
		   $db->query2($SQL,"防止文件外发");
		   $i++;   
		  }
		  
		 
         switch($sym)
         {
            case '0':
               $SQL2 = "update globalpara set isfiletypeopen=$isfileout";    		
    		  break;
    	    case '1':
    	       $SQL2 = "update globalpara set ismailopen=$isfileout";    		   	
		      break;
    	    case '2':
    	        $SQL2 = "update globalpara set isbbsopen=$isfileout";    
			   break;
			 case '3':
    	        $SQL2 = "update globalpara set isimopen=$isfileout";    
			   break;   
      	      case '4':
    	        $SQL2 = "update globalpara set isnetdiskopen=$isfileout";    
			   break;
              case '5':
    	        $SQL2 = "update globalpara set isblogopen=$isfileout";    
			   break; 
              case '6':
    	        $SQL2 = "update globalpara set isftpopen=$isfileout";    
			   break;
              case '7':
    	        $SQL2 = "update globalpara set istftpopen=$isfileout";    
			   break;			   
         }
		$db->query2($SQL2,"防止文件外发"); 
      	
	  
	};
 
?>