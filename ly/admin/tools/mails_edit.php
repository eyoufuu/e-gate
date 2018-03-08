
<?php 
   
    require_once('_inc.php');
	  
   $rev_oper = $_REQUEST['oper'];
  
    switch($rev_oper)
    {
      case 'del':
    		$rev_id = $_POST['id'];
    	    $SQL = "delete from mail_spec where id=$rev_id;";
			$db->exec($SQL);
			//$SQL2 = "delete from specweb where `host`='$key';";
			//$db->exec($SQL2);
    		break;
    	 case 'add':
    	    $rev_send = $_REQUEST['send'];
		   	$rev_accept = $_REQUEST['accept'];
			$rev_title = $_REQUEST['title'];
			$rev_content = $_REQUEST['content'];
			$rev_block = $_REQUEST['block'];
			//$rev_filetype="web邮箱";
		    $SQL = "insert into mail_spec(`send`,`accept`,`title`,`content`,`block`) values('$rev_send','$rev_accept','$rev_title','$rev_content',$rev_block)";
            echo $SQL;
			$result =$db->exec($SQL);
			break;
    	  case 'edit':
               $rev_id = $_REQUEST['id'];        	      
			   $rev_send = $_REQUEST['send'];
		   	   $rev_accept = $_REQUEST['accept'];
			   $rev_title = $_REQUEST['title'];
			   $rev_content = $_REQUEST['content'];
			   $rev_block = $_REQUEST['block'];		   
               $SQL = "update mail_spec set `send`='$rev_send',`accept`='$rev_accept',`title`='$rev_title',`content`='$rev_content',block=$rev_block  where id=$rev_id";			   
               //$SQL = "update file_transter set address='$rev_address',function='$rev_function' where address='$rev_address' ";
			  // echo $SQL;
			   $db->query2($SQL);
			   break;
      	      
       }

	  echo json_encode(array('success'=>true,'message'=>"保存成功"));return;

?>