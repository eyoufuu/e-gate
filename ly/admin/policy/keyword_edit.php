<?php
   require_once('_inc.php');
  
?>
<?php 

    $rev_oper = $_POST['oper'];
    
    switch($rev_oper)
    {
      case 'del':
          $rev_keyword = $_POST['keyword'];
          $id = $_POST['id'];
       
          $SQL = "select keywordinfo from policy where policyid=0;";
		    $keyword =$db->fetchOne($SQL);
		   	if ($keyword=="")
		   	return;
		     	  $keywordarr=explode('|',trim($keyword));
              $i=1;
              $arrayc=count($keywordarr);
              if ($arrayc==1 && $id==1)
              $key=" ";
              else
              {
               foreach ($keywordarr as $row)
                   {
                     if($i==$id)
                      {
                        $i++;
                        continue;
                      }
                    
                     else  
                      {                     
                       if ($i==1)
                        $key=$row;
                       else
    	                  $key=$key."|".$row;
    	                 }                    
                     $i++;
                   };
                }       
			   $SQL = "update policy set keywordinfo='$key' where policyid=0";
				$result =$db->exec($SQL);
    		break;
    	 case 'add':
    	       $rev_keyword = $_POST['keyword'];
		   	 $rev_block = $_POST['block'];
		       $rev_log = $_POST['log'];
    	     
            $SQL = "select keywordinfo from policy where policyid=0;";
		   	$keyword =$db->fetchRow($SQL);
		   	$keywords=$keyword[keywordinfo];
		   	if ($keywords=="")
		   	 {
               if ($rev_block =='阻挡' && $rev_log == '是' ) 
		           $symbol='3';
		       	else if ($rev_block == '阻挡')
		      	  $symbol='1';
		    	   else if ($rev_log == '是')
		      	  $symbol='2';
			     	$keywords=$rev_keyword.",".$symbol;
		   	 }
		   	else
		   	 {
		        if ($rev_block =='阻挡' && $rev_log == '是' ) 
		          $symbol='3';
		       	else if ($rev_block == '阻挡')
		      	 $symbol='1';
		    	   else if ($rev_log == '是')
		      	 $symbol='2';
			     	$keywords=$keywords."|".$rev_keyword.",".$symbol;
		        }     	
			   $SQL = "update policy set keywordinfo='$keywords' where policyid=0";
				$result =$db->exec($SQL);
		      
		 
  		
			 break;
    	 case 'edit':
    	       $rev_keyword = $_POST['keyword'];
		   	 $rev_block = $_POST['block'];
		       $rev_log = $_POST['log'];
		       $id = $_POST['id'];
    	     
            $SQL = "select keywordinfo from policy where policyid=0;";
		   	$keyword =$db->fetchOne($SQL);
		   	$keywordarr=explode('|',trim($keyword));  
                 $i=1;		   	  
		   	   foreach ($keywordarr as $row)
                    {
                      if($i==$id)
                       {  
                       if ($rev_block =='阻挡' && $rev_log == '是' ) 
		                    $symbol='3';
		       	        else if ($rev_block == '阻挡')
		      	           $symbol='1';
		    	           else if ($rev_log == '是')
		      	           $symbol='2';
                       $rows=$rev_keyword.",".$symbol;                         
                         if ($i==1)   
                           $key=$rows;
                         else
                           $key=$key."|".$rows; 
                        }
                       else
                        {
                          if ($i==1)   
                            $key=$row;
                          else
                            $key=$key."|".$row;
                        }
                       $i++;                   
                   };
		             	
			   $SQL = "update policy  set keywordinfo='$key' where policyid=0";
				$result =$db->exec($SQL);
		      
                	   
    	   
    	   
			      			  
			  break;
      	default:    		
    		return;	
       }

	  echo json_encode(array('success'=>true,'message'=>"保存成功"));return;

?>