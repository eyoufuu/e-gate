<?php
   require_once('_inc.php');
  
?>
<?php 
   
    $rev_oper = $_POST['oper'];
    switch($rev_oper)
    {
      case 'del':
    		$rev_id = $_POST['id'];
    	    $SQL = "delete from netseg where id in($rev_id);";
		    $db->exec($SQL,"用户管理");
			system($gCmd." 1");
    		break;
    	 case 'add':
    	     
		   	
            $SQL = "select count(*) from useraccount where name=$rev_name;";
		    	$count =$db->fetchOne($SQL);
             if($count == 0)
			      {
			        $rev_policyid = $_POST['policyid'];
		   	     $rev_name = $_POST['name'];
		   	
		   	     $rev_ips = ip2long($_POST['ips']);
		   	     $rev_ipe = ip2long($_POST['ipe']);
		   	     $rev_monitor = $_POST['monitor'];
		   	
			       $format = "insert into netseg(`name`,`ips`,`ipe`,`monitor`,`policyid`) values('%s',%u,%u,%u,%u)";
				    $SQL = sprintf($format,$rev_name,$rev_ips,$rev_ipe,$rev_monitor, $rev_policyid);  		   
				    $result =$db->exec($SQL,"用户管理");
				    system($gCmd." 1");
		      	}
			     else
			      {
					    echo json_encode(array('success'=>false,'message'=>"ip数据已存在"));return;			
					}  		   	
		 
  		
			 break;
    	 case 'edit':
    	       
    	   
    	      $rev_id = $_POST['id'];
    	      $rev_policyid = $_POST['policyid'];
		   	  $rev_name = $_POST['name'];
		   	  $rev_ips =ip2long($_POST['ips']);
		   	  $rev_ipe = ip2long($_POST['ipe']);
		   	  $rev_monitor = $_POST['monitor'];
		   	  $format = "update netseg set name='%s',ips=%u,ipe=%u,monitor=%u,policyid=%u where id=%u";
			  $SQL = sprintf($format,$rev_name,$rev_ips,$rev_ipe,$rev_monitor, $rev_policyid,$rev_id);  		
              $result = $db->query2($SQL,"用户管理");
		      system($gCmd." 1"); 		
			  
			      			  
			  break;
      	default:    		
    		return;	
       }

	  echo json_encode(array('success'=>true,'message'=>"保存成功"));return;

?>