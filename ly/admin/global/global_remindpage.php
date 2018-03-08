<?php
  require_once('_inc.php');
	
    switch($_POST['remind']) 
    {
	   	case "0":
	    		$SQL="update globalpara set isremindpage=0";
	    		$result = $db->exec($SQL,"客户端登陆方式");	     		
	    		break;
		case "1":
				$SQL="update globalpara set isremindpage=1";
				$result = $db->exec($SQL,"客户端登陆方式");
			   break;
		default:
			break;
    }
?>