
<?php 
   
    require_once('_inc.php');
	  
   $rev_oper = $_POST['oper'];
  
    switch($rev_oper)
    {
      case 'del':
    		$key = $_POST['key'];
    	    $SQL = "delete from file_transter where `address`='$key';";
			$db->exec($SQL);
			//$SQL2 = "delete from specweb where `host`='$key';";
			//$db->exec($SQL2);
    		break;
    	 case 'add':
    	    $rev_key = $_POST['key'];
		   	$rev_function = $_POST['function'];
		   	$rev_address = $_POST['address'];
			
		    $SQL = "select count(*) from file_transter where `key`='$rev_key';";
		    $count =$db->fetchOne($SQL);
             if($count == 0)
			      {
			        $SQL = "insert into file_transter(`function`,`key`,`address`) values('$rev_function','$rev_key','$rev_address')";
				    $result =$db->exec($SQL);
				   // $SQL2 = "insert into specweb(`host`,pass) values('$rev_address',$rev_block)";
				   // $result =$db->exec($SQL2);
				  }
			     else
			      {
					 echo json_encode(array('success'=>false,'message'=>"数据已存在"));return;			
				  }  		   	
		    break;
    	  case 'edit':
    	       $rev_key = $_POST['key'];
		   	   $rev_function = $_POST['function'];
		   	   $rev_address = $_POST['address'];    
               $SQL = "update file_transter set `key`='$rev_key',`address`='$rev_address',`function`='$rev_function' where `key`='$rev_key' ";			   
               //$SQL = "update file_transter set address='$rev_address',function='$rev_function' where address='$rev_address' ";
			  // echo $SQL;
			   $db->query2($SQL);
			   break;
      	       default:    		
    		   return;	
       }

	  echo json_encode(array('success'=>true,'message'=>"保存成功"));return;

?>