
<?php 
   
    require_once('_inc.php');
	  
   $rev_oper = $_POST['oper'];
  
    switch($rev_oper)
    {
      case 'del':
    		$rev_id = $_POST['id'];
    	    $SQL = "delete from file_transter where id=$rev_id;";
			$db->exec($SQL,"防止文件外发");
			//$SQL2 = "delete from specweb where `host`='$key';";
			//$db->exec($SQL2);
    		break;
    	 case 'add':
    	    $rev_key = $_REQUEST['key'];
		   	$rev_address = $_REQUEST['address'];
			$rev_block = $_REQUEST['block'];
			$rev_filetype="web邮箱";
		    $SQL = "select count(*) from file_transter where `key`='$rev_key';";
		    $count =$db->fetchOne($SQL);
             if($count == 0)
			      {
			        $SQL = "insert into file_transter(`key`,`address`,`function`,`block`) values('$rev_key','$rev_address','$rev_filetype',$rev_block)";
                  		   
				   $result =$db->exec($SQL,"防止文件外发");
				   // $SQL2 = "insert into specweb(`host`,pass) values('$rev_address',$rev_block)";
				   // $result =$db->exec($SQL2);
				  }
			     else
			      {
					 echo json_encode(array('success'=>false,'message'=>"数据已存在"));return;			
				  }  
		    break;
    	  case 'edit':
               $rev_id = $_POST['id'];        	      
			   $rev_key = $_POST['key'];
		   	   $rev_function = "web邮箱";
		   	   $rev_address = $_POST['address'];
               $rev_block = $_POST['block']; 			   
               $SQL = "update file_transter set `key`='$rev_key',`address`='$rev_address',`function`='$rev_function',block=$rev_block  where id=$rev_id";			   
               //$SQL = "update file_transter set address='$rev_address',function='$rev_function' where address='$rev_address' ";
			  // echo $SQL;
			   $db->query2($SQL,"防止文件外发");
			   break;
      	      
       }

	  echo json_encode(array('success'=>true,'message'=>"保存成功"));return;

?>