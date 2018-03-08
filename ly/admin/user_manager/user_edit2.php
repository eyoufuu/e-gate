<?php
   require_once('_inc.php');
  
?>
<?php 
   
   $sql_loginmode="select systemmode from globalpara;";
   $arr_mode=$db->fetchRow($sql_loginmode);
   $loginmode=$arr_mode['systemmode'];
   
	  
   $rev_oper = $_POST['oper'];
  
    switch($rev_oper)
    {
      case 'del':
    		$rev_id = $_POST['id'];
    	    $SQL = "delete from useraccount where id in($rev_id);";
		    $db->exec($SQL,"用户管理");
			system($gCmd ." 2");
    		break;
    	 case 'add':
    	  if ($loginmode=='0')
    	     { 
    	     
    	      $rev_policyid = $_POST['policyid'];
		   	$rev_name = $_POST['name'];
		   	$rev_bindip = ip2long($_POST['bindip']);
            $SQL = "select count(*) from useraccount where bindip=$rev_bindip;";
		    	$count =$db->fetchOne($SQL);
             if($count == 0)
			      {
			       $format = "insert into useraccount(`name`,`bindip`,`policyid`) values('%s',%u,%u)";
				    $SQL = sprintf($format,$rev_name,$rev_bindip, $rev_policyid);  	
				    $result =$db->exec($SQL,"用户管理");
				    system($gCmd ." 2");
		      	}
			     else
			      {
					    echo json_encode(array('success'=>false,'message'=>"ip数据已存在"));return;			
					}  		   	
		    }
  			else
  			  {
            
            $rev_passwd = $_POST['passwd'];
	   		$rev_account = $_POST['account'];
		   	$rev_name = $_POST['name'];
		   	$rev_policyid = $_POST['policyid'];
      	   $SQL = "select count(*) from useraccount where account='$rev_account';";
      	   $count = $db->fetchOne($SQL);
      	    if($count == 0)
			     {
				    $SQL = "insert into useraccount(`name`,`account`,`passwd`,`policyid`) values('$rev_name','$rev_account','$rev_passwd','$rev_policyid')";
				    $result = $db->exec($SQL,"用户管理");
		      	}
			     else
			      {
					  echo json_encode(array('success'=>false,'message'=>"account数据已存在"));return;			
			      }		    	
		    	 } ;			
						
			 break;
    	 case 'edit':
    	       
    	       if ($loginmode=='0')
    	     { 
    	      $rev_id = $_POST['id'];
    	      $rev_policyid = $_POST['policyid'];
		   	  $rev_name = $_POST['name'];
		   	  $rev_bindip =ip2long($_POST['bindip']);
		   	  $format = "update useraccount set name='%s',bindip=%u,policyid=%u where id=%u";
			  $SQL = sprintf($format,$rev_name,$rev_bindip, $rev_policyid,$rev_id);  	
			  $result = $db->exec($SQL,"用户管理");
			  system($gCmd ." 2");
		     }
  			else
  			  {
            $rev_id = $_POST['id'];
            $rev_passwd = $_POST['passwd'];
	   		$rev_account = $_POST['account'];
		   	$rev_name = $_POST['name'];
		   	$rev_policyid = $_POST['policyid'];
            // $SQL = "update useraccount set name='$rev_name',account='$rev_account',passwd='$rev_passwd',policyid=$rev_policyid where account='$rev_account' ";
			$SQL = "update useraccount set name='$rev_name',account='$rev_account',passwd='$rev_passwd',policyid=$rev_policyid where id=$rev_id ";
			$result = $db->query2($SQL,"用户管理"); 
		      			    	
		     } 		
			break;
      	    default:    		
    		return;	
       }

	  echo json_encode(array('success'=>true,'message'=>"保存成功"));return;

?>