<?php
	 require_once('_inc.php');
	
    switch($_POST['gate']) 
    {
	   	case "0":
	    		$SQL="update globalpara set gate=0";
	    		$result = $db->exec($SQL,"未监控网段");
	    		@exec($gCmd." 5");	     		
	    		break;
		case "1":
				$SQL="update globalpara set gate=1";
				$result = $db->exec($SQL,"未监控网段");
				@exec($gCmd." 5");	
			   break;
		default:
			break;
    }
?>